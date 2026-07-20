@extends('layouts.app')
@section('title', 'Dashboard')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <div class="container-fluid mt-3">
        <div class="row g-3">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Create Outlet</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="userForm">
                            {{-- ROLE --}}
                            <div class="row mb-3">
                                <div class="col-4">
                                    <x-form.input id="codeOutlet" label="Code Outlet" name="codeOutlet"
                                        placeholder="Code Outlet" type="text" valid="Masukkan Code Outlet Yang Valid" />
                                </div>
                                <div class="col-8">
                                    <x-form.input id="name" label="Nama Outlet" name="name"
                                        placeholder="Masukkan Nama Outlet" type="text"
                                        valid="Masukkan Nama Outlet Yang Valid" />
                                </div>
                            </div>

                            {{-- NAME --}}


                            {{-- USERNAME --}}
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" aria-label="With textarea" id="alamat"
                                    placeholder="Masukkan Alamat" name="alamat"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Simpan
                            </button>
                            <button type="button" class="btn btn-primary w-100 update-user" style="display: none">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Data Outlet</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table id="outletTable" class="table style-3 dt-table-hover">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center col-no">No</th>
                                        <th>Code</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">Status</th>
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
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            let outletTable = $('#outletTable').DataTable({
                processing: true,
                ajax: {
                    url: '/master/outlet/items',
                    dataSrc: function (json) {
                        console.log(json.data);
                        return json.data;
                    }
                },
                columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'codeOutlet'
                },
                {
                    data: 'name',
                    className: 'text-center'
                },
                {
                    data: 'alamat',
                    className: 'text-center'
                },
                {
                    data: 'is_active',
                    className: 'text-center',
                    render: function (data, row, type, meta) {
                        if (data === 1) {
                            return '<span class="badge badge-success">Aktif</span>';
                        } else {
                            return '<span class="badge badge-danger">Nonaktif</span>';
                        }
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    orderable: false,
                    render: function (data, row, type, meta) {
                        if (type.is_active === 1) {
                            return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item btn-edit" data-id="${data}">Edit</a></li>
                                <li><a class="dropdown-item text-danger btn-delete" data-id="${data}">Delete</a></li>
                            </ul>
                        </div>
                    `;
                        } else {
                            return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item btn-edit" data-id="${data}">Edit</a></li>
                                <li><a class="dropdown-item text-success btn-activate" data-id="${data}">Activate</a></li>
                            </ul>
                        </div>
                    `;
                        }
                    }
                }
                ]
            });

            $('#userForm').on('submit', function (e) {
                e.preventDefault();

                let btn = $(this).find('button[type="submit"]');

                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: '/master/outlet/create',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (res) {

                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Berhasil disimpan'
                        });

                        $('#userForm')[0].reset();

                        outletTable.ajax.reload();

                        btn.prop('disabled', false).text('Simpan');
                    },
                    error: function (err) {
                        btn.prop('disabled', false).text('Simpan');

                        let msg = err.responseJSON?.message || 'Terjadi kesalahan';

                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });

            $(document).on('click', '.btn-edit', function () {
                let data = outletTable.row($(this).closest('tr')).data();

                $('#name').val(data.name);
                $('#codeOutlet').val(data.codeOutlet);
                $('#alamat').val(data.alamat);

                $('.update-user')
                    .attr('data-id', data.id)
                    .show();

                $('#userForm button[type="submit"]').hide();

                // scroll ke form
                $('html, body').animate({
                    scrollTop: $('#userForm').offset().top - 100
                }, 300);
            });

            $('.update-user').on('click', function () {
                let id = $(this).data('id');
                let btn = $(this);

                btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `/master/outlet/update/${id}`,
                    method: 'PATCH',
                    data: $('#userForm').serialize(),
                    success: function (res) {

                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Berhasil diupdate'
                        });

                        $('#userForm')[0].reset();

                        $('.update-user').hide();
                        $('#userForm button[type="submit"]').show();

                        outletTable.ajax.reload();

                        btn.prop('disabled', false).text('Update');
                    },
                    error: function () {
                        btn.prop('disabled', false).text('Update');

                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal update'
                        });
                    }
                });
            });

            $(document).on('click', '.btn-delete', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data akan dihapus',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/master/outlet/delete/${id}`,
                            method: 'DELETE',
                            success: function (res) {

                                Toast.fire({
                                    icon: 'success',
                                    title: res.message || 'Berhasil dihapus'
                                });

                                outletTable.ajax.reload();
                            }
                        });
                    }
                });
            });


            $(document).on('click', '.btn-activate', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data akan diAktifkan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, aktifkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/master/outlet/activate/${id}`,
                            method: 'PATCH',
                            success: function (res) {

                                Toast.fire({
                                    icon: 'success',
                                    title: res.message || 'Berhasil diaktifkan'
                                });

                                outletTable.ajax.reload();
                            }
                        });
                    }
                });
            });

        })
    </script>
@endsection