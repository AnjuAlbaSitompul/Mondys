@extends('layouts.app')
@section('title', 'Barang')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <div class="row layout-top-spacing">

        <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tambahkan Barang Untuk Diambil / Barang Yang Dititipkan</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form class="row g-3" id="barangForm">
                        <div class="col-md-4">
                            <x-form.select :options="[
                                '' => 'Pilih Tipe Barang',
                                'sj' => 'Surat Jalan',
                                'titip' => 'Titip',
                            ]" name="type" label="Tipe Barang" id="type" />
                        </div>
                        <div class="col-lg-12 titip-section">
                            <x-form.select label="Dari Outlet" name="outletId" :options="[
                                null => 'Pilih Outlet',
                                ...$outlets,
                            ]" />
                        </div>
                        <div class="col-lg-12 sj-section">
                            <x-form.input-btn placeholder="Masukkan Code SJ" btnTxt="Scan" btnId="suratjalanBtn"
                                name="codesj" type="text" invalid="Harap Masukkan Code SJ" label="Surat Jalan"
                                id="sj" disabled="{{ false }}" toggle="modal" target="#scannerModal" />
                        </div>
                        <div class="col-lg-12 picker-section">
                            <x-form.input-btn placeholder="Pilih Picker" btnTxt="Choose" btnId="choosePicker" name="picker"
                                type="text" invalid="Harap Masukkan Picker" label="Pilih Picker" id="picker"
                                disabled="{{ true }}" target="#pickerModal" toggle="modal" />
                            <input type="hidden" id="pickerId" name="pickerId">
                        </div>
                        <div class="col-lg-12 desc-section">
                            <x-form.input label="Masukkan Quantity" name="qty" type="number"
                                valid="Silahkan Isi Quantity" />
                        </div>
                        <div class="col-lg-12 desc-section">
                            <textarea name="desc" class="form-control" placeholder="Masukkan Deskripsi Barang"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 me-4 btn-section">Tambahkan Barang</button>
                    </form>
                    <x-modal.scan-modal inputId="barcode" />
                    <x-modal.picker-modal inputId="userPicker" />
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Data Barang</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.sj-section').hide();
            $('.titip-section').hide();
            $('.picker-section').hide();
            $('.desc-section').hide();
            $('.btn-section').hide()

            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            $('#type').change(function() {

                let value = $(this).val();

                if (value === 'sj') {
                    $('.sj-section').show();
                    $('.titip-section').hide();
                    $('.picker-section').show();
                    $('.desc-section').show();
                    $('.btn-section').show();


                } else if (value === 'titip') {
                    $('.sj-section').hide();
                    $('.titip-section').show();
                    $('.picker-section').hide();
                    $('.desc-section').show();
                    $('.btn-section').show();

                } else {
                    $('.sj-section').hide();
                    $('.titip-section').hide();
                    $('.picker-section').hide()
                    $('.desc-section').hide();
                    $('.btn-section').hide()
                }

            });

            $(document).on('click', '.pilih-picker', function() {

                let id = $(this).data('id');
                let name = $(this).data('name');
                $('#picker').val(name);
                $('#pickerId').val(id);
                $('#pickerModal').modal('hide');

            });

            $('#barangForm').on('submit', function(e) {
                e.preventDefault()
                let data = $(this).serializeArray()
                $.ajax({
                    url: '/barang',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response.data)
                        Toast.fire({
                            icon: 'success',
                            title: 'Barang Sudah Di Tambahkan'
                        })
                        $('.invalid-feedback').hide().text('');
                        $('.form-control').removeClass('is-invalid');
                        $('#barangForm')[0].reset();
                        $('.sj-section, .titip-section, .picker-section, .desc-section, .btn-section')
                            .hide();
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message ?? 'Login gagal';
                        let errors = xhr.responseJSON.data;
                        console.log(errors)
                        $('.invalid-feedback').hide();
                        $('.form-control').removeClass('is-invalid');

                        $.each(errors, function(field, message) {

                            let input = $('[name="' + field + '"]');
                            let feedback = input.next('.invalid-feedback');
                            input.addClass('is-invalid');

                            feedback.text(message[0]);
                            feedback.show();

                        });
                        Toast.fire({
                            icon: 'error',
                            title: message
                        });
                    }
                });
            })


        })
    </script>
@endsection
