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
                        <input type="hidden" value="sj" name="type">
                        <div class="col-lg-12">
                            <x-form.input-btn placeholder="Pilih Picker" btnTxt="Choose" btnId="choosePicker" name="picker"
                                type="text" invalid="Harap Masukkan Picker" label="Pilih Picker" id="picker"
                                disabled="{{ true }}" target="#pickerModal" toggle="modal" />
                            <input type="hidden" id="pickerId" name="pickerId">
                        </div>
                        <div class="col-lg-12">
                            <x-form.input-btn placeholder="Masukkan Code SJ" btnTxt="Scan" btnId="suratjalanBtn"
                                name="codesj" type="basic" invalid="Harap Masukkan Code SJ" label="Surat Jalan"
                                id="sj" disabled="{{ false }}" toggle="modal" target="#scannerModal"
                                value="tag1, tag2 autofocus" />
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 me-4 ">Tambahkan Picker</button>
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
                        <table id="barangTable" class="table style-3 dt-table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center col-no">No</th>
                                    <th>TUJUAN</th>
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-center col-action">
                                        <i class="fa fa-cog"></i>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

    <script>
        $(document).ready(function() {
            var input = document.querySelector('#sj');

            var tagify = new Tagify(input, {
                delimiters: ",",
                maxTags: 10
            });
            let tableBarang = $('#barangTable').DataTable({
                processing: true,
                ajax: {
                    url: '/barang/items',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columns: [{
                        data: null,
                        className: "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'outlet.name', // pastikan outlet relasi ada
                        defaultContent: '-' // untuk barang tanpa outlet
                    },
                    {
                        className: 'text-center',
                        data: 'type'
                    },
                    {
                        className: 'text-center',
                        data: 'status'
                    },
                    {
                        className: 'text-center',
                        data: 'boxqty'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        defaultContent: '-'
                    }
                ]
            });

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
                console.log(data)
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
                        tableBarang.ajax.reload(null, false);
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
