@extends('layouts.app')
@section('title', 'Barang')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <div class="row layout-top-spacing">

        <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
            <div class="simple-tab">

                <ul class="nav nav-tabs p-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#home-tab-icon-pane" type="button" role="tab"
                            aria-controls="home-tab-icon-pane" aria-selected="true">
                            <i class="fa fa-box"></i>
                            Start Pick
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                            aria-controls="profile-tab-icon-pane" aria-selected="false">
                            <i class="fa fa-truck"></i>
                            End Pick
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
                                        <h4>Boarding Operation</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form class="row g-3" id="bordingForm">
                                    <div class="col-lg-12">
                                        <x-form.input-btn placeholder="Masukkan Code Boarding" btnTxt="Scan"
                                            btnId="scanCode" name="codeBoarding" type="basic"
                                            invalid="Harap Masukkan Code Boarding" label="Code Boarding" id="codeBoarding"
                                            disabled="{{ false }}" toggle="modal" target="#scannerModal"
                                            value="tag1, tag2 autofocus" />
                                        <div class="text-muted">Format: HS09(4) + SJ(16) + PR(8) + Koli(2) = 30 chars</div>
                                    </div>
                                    <div class="col-lg-12">
                                        <x-form.input id="qty" label="Masukkan Jumlah Box" name="qty"
                                            type="number" min="1" placeholder="Masukkan Jumlah Box"
                                            valid="Masukkan Jumlah Box yang valid" />
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2 me-4 ">Tambahkan Ke Boarding</button>
                                </form>
                                <x-modal.scan-modal inputId="codeBoarding" />
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Data Boarding</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table id="boardingListTable" class="table style-3 dt-table-hover">
                                            <thead class="table-header">
                                                <tr>
                                                    <th class="text-center col-no">No</th>
                                                    <th class="text-center">TUJUAN</th>
                                                    <th class="text-center">TYPE</th>
                                                    <th class="text-center">BOARDING CODE</th>
                                                    <th class="text-center">STATUS</th>
                                                    <th class="text-center">DURATION</th>
                                                    <th class="text-center">Created By</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="profile-tab-icon-pane" role="tabpanel"
                        aria-labelledby="profile-tab-icon" tabindex="0">
                        @include('pages.titip')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            moment.locale('id')

            var input = document.querySelector('#codeBoarding');
            var tagify = new Tagify(input, {
                delimiters: ",",
                maxTags: 10
            });

            let tableBoarding = $('#boardingListTable').DataTable({
                processing: true,
                ajax: {
                    url: '/boarding/items',
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
                        data: 'outlet', // pastikan outlet relasi ada
                        defaultContent: '-' // untuk barang tanpa outlet
                    },
                    {
                        className: 'text-center',
                        data: 'type', // pastikan type relasi ada
                        defaultContent: '-', // untuk barang tanpa type
                        render: function(data, type, row) {
                            console.log(data)
                            if (data) {
                                return `<span class="badge badge-info">${data}</span>`;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'code',
                        defaultContent: '-' // untuk barang tanpa outlet

                    },
                    {
                        className: 'text-center',
                        data: 'boarding_end',
                        render: function(data, type, row) {
                            if (data) {
                                return `<span class="badge badge-success">BOARDING END</span>`;
                            } else {
                                return `<span class="badge badge-warning">BOARDING</span>`;
                            }
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'started_at',
                        render: function(data, type, row) {
                            if (type !== 'display') return data;

                            // kalau sudah selesai → hitung durasi
                            if (row.boarding_end) {
                                let start = moment.utc(data);
                                let end = moment.utc(row.boarding_end);

                                let duration = moment.duration(end.diff(start));

                                let hours = Math.floor(duration.asHours());
                                let minutes = duration.minutes();

                                return `${hours} jam ${minutes} menit`;
                            }

                            // kalau belum selesai → tampilkan waktu relatif
                            return moment.utc(data).local().fromNow();
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'created_by',
                        defaultContent: '-'
                    }
                ]
            });

            $('#bordingForm').on('submit', function(e) {
                e.preventDefault();

                let data = $(this).serializeArray()


                $.ajax({
                    url: '/boarding',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.error
                        });
                        $('#bordingForm')[0].reset();
                        tagify.removeAllTags();
                        tableBoarding.ajax.reload();
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        console.log(xhr.responseJSON.errors)
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMsg
                        });
                    }
                });
            });
        })
    </script>
@endsection
