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
                                <h4>Create Department</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="userForm">

                            {{-- ROLE --}}
                            <div class="row mb-3">
                                <div class="col-8">
                                    <x-form.input id="name" label="Nama Department" name="name"
                                        placeholder="Masukkan Nama Department" type="text"
                                        valid="Masukkan Nama Department Yang Valid" />
                                </div>
                                <div class="col-4">
                                    <x-form.input id="code" label="Code Department" name="code"
                                        placeholder="Code Department" type="text"
                                        valid="Masukkan Code Department Yang Valid" />
                                </div>

                            </div>

                            {{-- NAME --}}


                            {{-- USERNAME --}}
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" aria-label="With textarea" id="alamat" placeholder="Masukkan Alamat" name="address"></textarea>
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
                                <h4>Data Department</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table id="departmentTable" class="table style-3 dt-table-hover">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center col-no">No</th>
                                        <th>Code</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Alamat</th>
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
        $(document).ready(function() {
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

            let departmentTable = $('#departmentTable').DataTable({
                processing: true,
                ajax: {
                    url: '/master/department/items',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'name',
                        className: 'text-center'
                    },
                    {
                        data: 'address',
                        className: 'text-center'
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        orderable: false,
                        render: function(data) {
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
                        }
                    }
                ]
            });

            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                let btn = $(this).find('button[type="submit"]');

                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: '/master/department/create',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {

                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Berhasil disimpan'
                        });

                        $('#userForm')[0].reset();

                        departmentTable.ajax.reload();

                        btn.prop('disabled', false).text('Simpan');
                    },
                    error: function(err) {
                        btn.prop('disabled', false).text('Simpan');

                        let msg = err.responseJSON?.message || 'Terjadi kesalahan';

                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });

            $(document).on('click', '.btn-edit', function() {
                let data = departmentTable.row($(this).closest('tr')).data();

                $('#name').val(data.name);
                $('#code').val(data.code);
                $('#alamat').val(data.address);

                $('.update-user')
                    .attr('data-id', data.id)
                    .show();

                $('#userForm button[type="submit"]').hide();

                // scroll ke form
                $('html, body').animate({
                    scrollTop: $('#userForm').offset().top - 100
                }, 300);
            });

            $('.update-user').on('click', function() {
                let id = $(this).data('id');
                let btn = $(this);

                btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `/master/department/update/${id}`,
                    method: 'PATCH',
                    data: $('#userForm').serialize(),
                    success: function(res) {

                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Berhasil diupdate'
                        });

                        $('#userForm')[0].reset();

                        $('.update-user').hide();
                        $('#userForm button[type="submit"]').show();

                        departmentTable.ajax.reload();

                        btn.prop('disabled', false).text('Update');
                    },
                    error: function() {
                        btn.prop('disabled', false).text('Update');

                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal update'
                        });
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
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
                            url: `/master/department/delete/${id}`,
                            method: 'DELETE',
                            success: function(res) {

                                Toast.fire({
                                    icon: 'success',
                                    title: res.message || 'Berhasil dihapus'
                                });

                                departmentTable.ajax.reload();
                            }
                        });
                    }
                });
            });
        })
    </script>
@endsection
