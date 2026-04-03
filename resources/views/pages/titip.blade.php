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
                            <h4>Titipan</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form class="row g-3" id="titipForm">
                        <input type="hidden" value="TITIP" name="type">
                        <div class="col-lg-12">
                            <x-form.select id="outlet" label="Tujuan Outlet" name="outletId" :options="[null => 'Pilih Outlet'] + $outlets" />
                        </div>
                        <div class="col-lg-9">
                            <x-form.input placeholder="Masukan Nama Barang" type="Text" name="namaBarang" id="namaBarang"
                                label="Masukkan Nama Barang" />
                        </div>
                        <div class="col-lg-3">
                            <x-form.input placeholder="Masukan Jumlah Koli" type="NUMBER" name="koli" id="koli"
                                label="Masukkan Jumlah Koli" />
                        </div>
                        <div class="col-lg-12">
                            <x-form.select id="jenis-barang" label="Jenis Barang" name="jenisBarangId" :options="[null => 'Pilih Jenis Barang'] + $jenisBarangs" />
                        </div>
                        <button type="button" class="btn btn-primary mb-2 me-4 add-btn">Tambahkan Barang Titipan</button>
                        <button type="submit" class="btn btn-primary mb-2 me-4 update-btn" style="display: none;">Update
                            Barang Titipan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>List Titipan</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table id="titipTable" class="table style-3 dt-table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center col-no">No</th>
                                    <th class="text-center">TUJUAN</th>
                                    <th class="text-center">NAMA BARANG</th>
                                    <th class="text-center">JENIS BARANG</th>
                                    <th class="text-center">JUMLAH KOLI</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">DURASI</th>
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

    <script>
        $(document).ready(function() {
            moment.locale('id');

            $titipTable = $('#titipTable').DataTable({
                processing: true,
                ajax: {
                    url: '/titip/items',
                    type: 'GET',
                    dataSrc: function(json) {
                        console.log(json);
                        return json.data
                    }
                },
                columns: [{
                        className: 'text-center',
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'outlet.name',
                        name: 'outlet.name'
                    },
                    {
                        className: 'text-center',
                        data: 'barang.nama_barang',
                        name: 'Nama Barang',
                    },
                    {
                        className: 'text-center',
                        data: 'barang.jenis_barang.name',
                        name: 'Jenis Barang',
                    },
                    {
                        className: 'text-center',
                        data: 'koli',
                    },
                    {
                        className: 'text-center',
                        data: 'boarding_end',
                        render: function(data) {
                            if (!data) {
                                return `<span class="badge badge-warning mb-2 me-4">Boarding</span>`;
                            } else {
                                return `<span class="badge badge-success mb-2 me-4">Delivered</span>`;
                            }

                        }
                    },
                    {
                        className: 'text-center',
                        data: 'boarding_start',
                        render: function(data, type, row) {
                            return moment.utc(data).local().fromNow();
                        }
                    },
                    {
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                              <div class="dropdown">
                                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                        </a>
    
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                            <a class="dropdown-item delete-btn" data-id="${row.id}">Delete</a>
                                                            <a class="dropdown-item update-picker" data-id="${row.id}">Update</a>
                                                        </div>
                                                    </div>`;
                        }
                    }
                ]
            });

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
                            url: `/titip/${id}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Terhapus!',
                                    response.message,
                                    'success'
                                );
                                $titipTable.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Gagal!',
                                    xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.update-picker', function() {
                let id = $(this).data('id');
                let dataTable = $titipTable.row($(this).parents('tr')).data();
                $('#outlet').val(dataTable.outlet.id);
                $('#jenis-barang').val(dataTable.barang.jenis_barang.id);
                $('#koli').val(dataTable.koli);
                $('#namaBarang').val(dataTable.barang.nama_barang);
                console.log(id);
                $('.add-btn').hide();
                $('.update-btn').show().attr('data-id', id);
            });

            $('.update-btn').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                const formData = $('#titipForm').serialize();
                $.ajax({
                    url: `/titip/update/${id}`,
                    method: 'PATCH',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        });
                        $('#titipTable').DataTable().ajax.reload();
                        $('#titipForm')[0].reset();
                        $('.add-btn').show();
                        $('.update-btn').hide().data('id', '');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat mengupdate barang titipan.',
                        });
                    }
                });
            });

            $('.add-btn').on('click', function(e) {
                e.preventDefault();
                const formData = $('#titipForm').serialize();
                $.ajax({
                    url: '/titip',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        });
                        $('#titipTable').DataTable().ajax.reload();
                        $('#titipForm')[0].reset();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menambahkan barang titipan.',
                        });
                    }
                });
            });

        });
    </script>
@endsection
