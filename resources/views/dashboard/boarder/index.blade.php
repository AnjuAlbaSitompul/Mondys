<div class="row layout-top-spacing">

    {{-- CARD 1 --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <!-- LEFT: GREETING -->
                    <div class="col-md-8 text-start">
                        <h5 class="mb-1 fw-semibold">
                            Hai, {{ $user->name }} 👋
                        </h5>
                        <p class="text-muted mb-0">
                            Selamat datang kembali. Pantau aktivitas boarding kamu hari ini dengan lebih mudah.
                        </p>
                    </div>

                    <!-- RIGHT: STATS -->
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="d-flex align-items-center justify-content-md-end gap-3">

                            <div class="bg-primary-subtle text-primary rounded-3 p-3">
                                <i class="fa-solid fa-box fa-lg"></i>
                            </div>

                            <div class="text-start text-md-end">
                                <small class="text-muted d-block">
                                    Total Boarding Hari Ini
                                </small>
                                <h3 class="fw-bold mb-0">
                                    {{ $boardingCount }}
                                </h3>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="simple-tab">

        <ul class="nav nav-tabs p-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab"
                    data-bs-target="#home-tab-icon-pane" type="button" role="tab"
                    aria-controls="home-tab-icon-pane" aria-selected="true">
                    <i class="fa fa-box"></i>
                    Boarding
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                    data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                    aria-controls="profile-tab-icon-pane" aria-selected="false">
                    <i class="fa fa-truck"></i>
                    Titip
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-icon-pane" role="tabpanel"
                aria-labelledby="home-tab-icon" tabindex="0">
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
            <div class="tab-pane fade show" id="profile-tab-icon-pane" role="tabpanel"
                aria-labelledby="profile-tab-icon" tabindex="0">
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
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
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
            ]
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

        $('#confirmQty').on('click', function() {
            let id = $(this).attr('data-id');
            let qty = $('#koliCount').val();

            if (!qty) {
                alert('Qty wajib diisi');
                return;
            }

            // redirect ke route
            window.location.href = `/picker/print?id=${id}&qty=${qty}`;
        });
    });
</script>
