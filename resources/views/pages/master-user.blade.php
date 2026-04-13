@extends('layouts.app')
@section('title', 'User')
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
                                <h4>Create Users</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="userForm">

                            {{-- ROLE --}}
                            <div class="row mb-3">
                                <div class="col-8">
                                    <x-form.input id="name" label="Masukkan Nama User" name="name"
                                        placeholder="Nama User" type="text" valid="Masukkan Nama User Yang Valid" />
                                </div>
                                <div class="col-4">
                                    <x-form.select id="role" label="Masukkan Role User" name="role"
                                        :options="[
                                            null => 'Pilih Role',
                                            'ADMIN' => 'Admin',
                                            'PIC' => 'PIC',
                                            'SPV' => 'SPV',
                                            'DRIVER' => 'Driver',
                                            'PICKER' => 'Picker',
                                        ]" />
                                </div>

                            </div>

                            {{-- NAME --}}


                            {{-- USERNAME --}}
                            <div class="mb-3">
                                <x-form.input id="username" label="Masukkan Username" name="username"
                                    placeholder="Username" type="text" valid="Masukkan Username Yang Valid" />
                            </div>

                            {{-- PASSWORD --}}
                            <div class="mb-3">
                                <x-form.input id="password" label="Masukkan Password" name="password"
                                    placeholder="Password" type="password" valid="Masukkan password Yang Valid" />
                            </div>

                            {{-- DEPARTMENT (ADMIN) --}}
                            <div class="mb-3 d-none" id="departmentField">
                                <div class="col-12">
                                    <x-form.select id="location" label="Masukkan Department User" name="location_id"
                                        :options="[
                                            null => 'Pilih Department',
                                        ] + $locations" />
                                </div>
                            </div>

                            {{-- OUTLET (PIC) --}}
                            <div class="mb-3 d-none" id="outletField">
                                <div class="col-12">
                                    <x-form.select id="outlet" label="Masukkan Outlet User" name="outlet_id"
                                        :options="[
                                            null => 'Pilih Outlets',
                                        ] + $outlets" />
                                </div>
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
                                <h4>Data Users</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table id="userTable" class="table style-3 dt-table-hover">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center col-no">No</th>
                                        <th>Nama</th>
                                        <th class="text-center">Role</th>
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
            });


            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let btn = form.find('button[type="submit"]');

                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: '/master/user/create',
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // success feedback
                        Toast.fire({
                            icon: 'success',
                            title: 'User berhasil di daftarkan'
                        });

                        // reset form
                        form[0].reset();

                        $('#departmentField, #outletField').addClass('d-none');

                        // reload datatable
                        $('#userTable').DataTable().ajax.reload();

                        // reset button
                        btn.prop('disabled', false).text('Simpan');
                    },
                    error: function(err) {
                        btn.prop('disabled', false).text('Simpan');

                        let errors = err.responseJSON?.errors;
                        let message = err.responseJSON?.message;
                        Toast.fire({
                            icon: 'error',
                            title: message || "Terjadi Kesalahan"
                        });
                    }
                });
            });

            $('#role').on('change', function() {
                let role = $(this).val();

                $('#departmentField, #outletField').addClass('d-none');

                if (role === 'PICKER') {
                    $('#departmentField').removeClass('d-none');
                }

                if (role === 'PIC') {
                    $('#outletField').removeClass('d-none');
                }
            });

            let userTable = $('#userTable').DataTable({
                processing: true,
                ajax: {
                    url: '/master/users/items',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'role',
                        className: 'text-center',
                        render: function(data) {
                            let color = data === 'ADMIN' ? 'primary' : 'warning';
                            return `<span class="badge bg-${color}">${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item btn-edit" data-id="${data}">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger btn-delete" data-id="${data}">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                `;
                        }
                    }
                ]
            });

            $(document).on('click', '.btn-edit', function() {
                let table = $('#userTable').DataTable();

                let rowData = table.row($(this).closest('tr')).data();

                // set value ke form
                $('#name').val(rowData.name);
                $('#username').val(rowData.username);
                $('#role').val(rowData.role).trigger('change');

                // location & outlet
                $('#location').val(rowData.location_id ?? '');
                $('#outlet').val(rowData.outlet_id ?? '');

                // toggle tombol
                $('.update-user')
                    .attr('data-id', rowData.id)
                    .show();

                $('#userForm button[type="submit"]').hide();
            });

            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "User Akan Di nonAktifkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/master/user/delete/${id}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'User berhasil dihapus'
                                });
                                userTable.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                const message = xhr.responseJSON?.message ??
                                    'Gagal menghapus user';
                                Toast.fire({
                                    icon: 'error',
                                    title: message
                                });
                            }
                        });
                    }
                })
            });

            $('.update-user').on('click', function() {
                let id = $(this).data('id');
                let btn = $(this);

                btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `/master/user/update/${id}`,
                    method: 'PATCH',
                    data: $('#userForm').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {

                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'User berhasil diupdate'
                        });

                        // reset form
                        $('#userForm')[0].reset();

                        // hide field conditional
                        $('#departmentField, #outletField').addClass('d-none');

                        // toggle button
                        $('.update-user').hide();
                        $('#userForm button[type="submit"]').show();

                        // reload datatable
                        $('#userTable').DataTable().ajax.reload();

                        btn.prop('disabled', false).text('Update');
                    },
                    error: function(err) {
                        btn.prop('disabled', false).text('Update');

                        let errors = err.responseJSON?.errors;

                        if (errors) {
                            let msg = Object.values(errors).flat().join('<br>');

                            Toast.fire({
                                icon: 'error',
                                title: msg
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan'
                            });
                        }
                    }
                });
            });
        })
    </script>
@endsection
