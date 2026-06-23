@extends('layouts.app')
@section('title', 'Picking')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <script src="https://unpkg.com/html5-qrcode"></script>

    <div class="row layout-top-spacing">

        <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
            <div class="simple-tab">

                <ul class="nav nav-tabs p-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation"> 
                        <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#home-tab-icon-pane" type="button" role="tab"
                            aria-controls="home-tab-icon-pane" aria-selected="true">
                            <i class="fa fa-box"></i>
                            Pick Start
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                            aria-controls="profile-tab-icon-pane" aria-selected="false">
                            <i class="fa fa-truck"></i>
                            Pick End
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-icon-pane" role="tabpanel"
                        aria-labelledby="home-tab-icon" tabindex="0">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>Picker Operation</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form class="row g-3" id="barangForm">
                                    <input type="hidden" value="sj" name="type">
                                    <div class="col-lg-12">
                                        <x-form.input-btn placeholder="Pilih Picker" btnTxt="Choose" btnId="choosePicker"
                                            name="picker" type="text" invalid="Harap Masukkan Picker"
                                            label="Pilih Picker" btnId="pickerBtn" id="picker"
                                            disabled="{{ true }}" target="#pickerModal" toggle="modal" />
                                        <input type="hidden" id="pickerId" name="pickerId">
                                    </div>
                                    <div class="col-lg-12">
                                        <x-form.input-btn placeholder="Masukkan Code SJ" btnTxt="Scan"
                                            btnId="suratjalanBtn" name="codesj" type="basic"
                                            invalid="Harap Masukkan Code SJ" label="Surat Jalan" id="sj"
                                            disabled="{{ false }}" value="tag1, tag2 autofocus" toggle=""
                                            target="" />
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2 me-4 ">Tambahkan Picker</button>
                                </form>

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
                                                    <th>PICKER</th>
                                                    <th class="text-center">SJ CODE</th>
                                                    <th class="text-center">STATUS</th>
                                                    <th class="text-center col-action">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="profile-tab-icon-pane" role="tabpanel"
                        aria-labelledby="profile-tab-icon" tabindex="0">
                        @include('pages.pick')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="Scanner" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Barcode Surat Jalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="reader" style="width:100%"></div>
                </div>
            </div>
        </div>
    </div>

    <x-modal.picker-modal inputId="userPicker" />
    <x-modal.update-picker inputId="userPicker" />

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        $(document).ready(function() {
            let html5QrCode;


            var input = document.querySelector('#sj');
            var pickEnd = document.querySelector('#sjpickend');

            var tagify = new Tagify(input, {
                delimiters: ",",
            });
            var tagifyPickEnd = new Tagify(pickEnd, {
                delimiters: ",",
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
                        data: 'picklist.picker.name', // pastikan outlet relasi ada
                        defaultContent: '-' // untuk barang tanpa outlet
                    },
                    {
                        className: 'text-center',
                        data: 'sjcode'
                    },
                    {
                        className: 'text-center',
                        data: 'status',
                        render: function(data, type, row) {

                            return `<span class="badge badge-success">${data.toUpperCase()}</span>`;

                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<div class="dropdown">
                                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                        </a>
    
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                            <a class="dropdown-item delete-btn" data-id="${row.id}">Delete</a>
                                                            <a class="dropdown-item update-picker">Update Picker</a>
                                                        </div>
                                                    </div>`;
                        }
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

            $(document).on('click', '.update-picker', function() {
                let rowData = tableBarang.row($(this).parents('tr')).data();
                let barangId = rowData.id || '';
                $('#updatePicker').attr('data-barang', barangId);
                $('#updatePicker').modal('show');
            });

            $(document).on('click', '.update-pickerbtn', function() {
                let barangId = $('#updatePicker').data('barang');
                let pickerId = $(this).data('id');

                console.log(barangId, pickerId);

                $.ajax({
                    url: `/barang/${barangId}/update-picker`,
                    method: 'PATCH',
                    data: {
                        pickerId: pickerId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Picker berhasil diupdate'
                        });
                        tableBarang.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message ?? 'Gagal mengupdate picker';
                        Toast.fire({
                            icon: 'error',
                            title: message
                        });
                    }
                });
            });

            $('#suratjalanBtn').on('click', function() {
                $('#scannerModal').modal('show');
                openModal(tagify)
            });


            $('#pickBtn').on('click', function() {
                $('#scannerModal').modal('show');
                openModal(tagifyPickEnd)
            });

            function openModal(input) {
                html5QrCode = new Html5Qrcode("reader");

                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    function(decodedText) {
                        input.addTags(decodedText);

                        html5QrCode.stop().then(() => {
                            $('#scannerModal').modal('hide');
                        });
                    }
                );
            };

            $('#scannerModal').on('hide.bs.modal', function() {
                html5QrCode.stop()
            })



            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/barang/${id}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Barang berhasil dihapus'
                                });
                                tableBarang.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                const message = xhr.responseJSON?.message ??
                                    'Gagal menghapus barang';
                                Toast.fire({
                                    icon: 'error',
                                    title: message
                                });
                            }
                        });
                    }
                })
            })
        });
    </script>
@endsection
