<div class="row layout-top-spacing">

    <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
        <div class="row align-items-center">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Dashboard Overview</h4>

            </div>
        </div>

        <div class="col-12">
            <div class="card bg-white mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">

                            <!-- kiri -->
                            <div class="d-flex align-items-center gap-2">

                                <div class="d-flex align-items-center gap-1 text-muted">
                                    <i class="fa fa-clock"></i>
                                    <span class="mb-0">Refreshed</span>
                                </div>

                                <input id="startDate" name="start_date" type="date" class="form-control mb-0"
                                    style="max-width: 180px;" />

                                <span class="mb-0">-</span>

                                <input id="endDate" name="end_date" type="date" class="form-control mb-0"
                                    style="max-width: 180px;" />
                            </div>

                            <!-- kanan -->
                            <button id="refresh" class="btn btn-primary btn-icon btn-rounded">
                                <i class="fa fa-refresh"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Operasional</h5>

                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="row">
                                <div class="card col-4">
                                    <div class="card-body">
                                        <small class="mb-0">Total SJ Diperoses</small>
                                        <h3 id="total_barang" class="card-title">{{ $total_barang }}</h3>
                                    </div>
                                </div>
                                <div class="card col-4">
                                    <div class="card-body">
                                        <small class="mb-0">SJ Selesai Loading</small>
                                        <h3 id="barang_loaded" class="card-title">{{ $barang_loaded }}</h3>
                                    </div>
                                </div>
                                <div class="card col-4">
                                    <div class="card-body">
                                        <small class="mb-0">Dalam Picking</small>
                                        <h3 id="barang_picking" class="card-title">{{ $barang_picking }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="row">
                                <div class="card col-4">
                                    <div class="card-body">
                                        <small class="mb-0">Dalam Boarding</small>
                                        <h3 id="barang_boarded" class="card-title">{{ $barang_boarded }}</h3>
                                    </div>
                                </div>
                                <div class="card bg-danger col-4">
                                    <div class="card-body">
                                        <small class="mb-0 ">Picking Error</small>
                                        <h3 id="slow_picking" class="card-title">{{ $slow_picking }}</h3>
                                    </div>
                                </div>
                                <div class="card col-4">
                                    <div class="card-body">
                                        <small class="mb-0">Total Picker Active</small>
                                        <h3 id="total_picker" class="card-title">{{ $total_picker }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Performa Picker</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table id="performaTable" class="table style-3 dt-table-hover">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center col-no">No</th>
                                        <th>Nama Picker</th>
                                        <th class="text-center col-no">Department</th>

                                        <th class="text-center">Total SJ</th>
                                        <th class="text-center">Rata - Rata Durasi</th>
                                        <th class="text-center">Pick Error</th>
                                        <th class="text-center">Performance</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="simple-tab">

                    <ul class="nav nav-tabs p-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab"
                                data-bs-target="#home-tab-icon-pane" type="button" role="tab"
                                aria-controls="home-tab-icon-pane" aria-selected="true">
                                <i class="fa fa-box"></i>
                                Picking
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                                aria-controls="profile-tab-icon-pane" aria-selected="false">
                                <i class="fa fa-truck"></i>
                                Boarding
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="loading-tab" data-bs-toggle="tab"
                                data-bs-target="#loading-tab-pane" type="button" role="tab"
                                aria-controls="loading-tab-pane" aria-selected="false">
                                <i class="fa fa-truck-loading"></i>
                                Loading
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="claim-tab" data-bs-toggle="tab"
                                data-bs-target="#claim-tab-pane" type="button" role="tab"
                                aria-controls="claim-tab-pane" aria-selected="false">
                                <i class="fa fa-file"></i>
                                Claim
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-icon-pane" role="tabpanel"
                            aria-labelledby="home-tab-icon" tabindex="0">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-10 col-md-10col-sm-10 col-10">
                                            <h4>Picking List</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table id="pickingTable" class="table style-3 dt-table-hover mb-3">
                                            <thead class="table-header">
                                                <tr>
                                                    <th class="text-center col-no">No</th>
                                                    <th class="text-center col-no">No. Surat Jalan</th>
                                                    <th class="text-center col-no">Picker</th>
                                                    <th class="text-center col-no">PIC Start</th>
                                                    <th class="text-center col-no">Timestamp Start</th>
                                                    <th class="text-center col-no">PIC end</th>
                                                    <th class="text-center col-no">Timestamp End</th>
                                                    <th class="text-center col-no">Durasi Picking</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="profile-tab-icon-pane" role="tabpanel"
                            aria-labelledby="profile-tab-icon" tabindex="0">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-10 col-md-10col-sm-10 col-10">
                                            <h4>Boarding List</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table id="boardingTable" class="table style-3 dt-table-hover mb-3">
                                            <thead class="table-header">
                                                <tr>
                                                    <th class="text-center col-no">No</th>
                                                    <th class="text-center col-no">No. Surat Jalan</th>
                                                    <th class="text-center col-no">Outlet</th>
                                                    <th class="text-center col-no">Box</th>
                                                    <th class="text-center col-no">Koli</th>
                                                    <th class="text-center col-no">PIC Boarding</th>
                                                    <th class="text-center col-no">Timestamp Boarding</th>
                                                    <th class="text-center col-no">Durasi Boarding</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="loading-tab-pane" role="tabpanel"
                            aria-labelledby="loading-tab-pane" tabindex="0">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-10 col-md-10col-sm-10 col-10">
                                            <h4>Loading List</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table id="loadingTable" class="table style-3 dt-table-hover mb-3">
                                            <thead class="table-header">
                                                <tr>
                                                    <th class="text-center col-no">No</th>
                                                    <th class="text-center col-no">No. Pengantaran</th>
                                                    <th class="text-center col-no">Outlet</th>
                                                    <th class="text-center col-no">Jumlah Box</th>
                                                    <th class="text-center col-no">Jumlah Koli</th>
                                                    <th class="text-center col-no">Driver / Co-Driver</th>
                                                    <th class="text-center col-no">Timestamp Loading</th>
                                                    <th class="text-center col-no">Durasi Loading</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="claim-tab-pane" role="tabpanel"
                            aria-labelledby="claim-tab-pane" tabindex="0">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-10 col-md-10col-sm-10 col-10">
                                            <h4>Loading List</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table id="claimTable" class="table style-3 dt-table-hover mb-3">
                                            <thead class="table-header">
                                                <tr>
                                                    <th class="text-center col-no">No</th>
                                                    <th class="text-center col-no">No SJ</th>
                                                    <th class="text-center col-no">Desc</th>
                                                    <th class="text-center col-no">Claimed At</th>
                                                    <th class="text-center col-no">Status</th>
                                                    <th class="text-center col-no">
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
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        moment.locale('id')
        let today = new Date();

        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let dd = String(today.getDate()).padStart(2, '0');

        let todayStr = `${yyyy}-${mm}-${dd}`;
        let firstDay = `${yyyy}-${mm}-01`;

        // set default value
        $('#startDate').val(firstDay);
        $('#endDate').val(todayStr);

        // set max (tidak boleh lebih dari hari ini)
        $('#startDate').attr('max', todayStr);
        $('#endDate').attr('max', todayStr);


        // biar end tidak bisa < start
        $('#startDate').on('change', function() {
            $('#endDate').attr('min', $(this).val());
        });

        // biar start tidak bisa > end
        $('#endDate').on('change', function() {
            $('#startDate').attr('max', $(this).val());
        });

        function loadDashboardSummary() {
            let start = $('#startDate').val();
            let end = $('#endDate').val();

            $.ajax({
                url: '/admin/dashboard/summary',
                type: 'GET',
                data: {
                    start_date: start,
                    end_date: end
                },
                success: function(res) {
                    let data = res.data;

                    $('#total_barang').text(data.total_barang);
                    $('#barang_loaded').text(data.barang_loaded);
                    $('#barang_picking').text(data.barang_picking);

                    $('#barang_boarded').text(data.barang_boarded);
                    $('#slow_picking').text(data.slow_picking);
                    $('#total_picker').text(data.total_picker);
                }
            });
        }


        let tablePicker = $('#performaTable').DataTable({
            processing: true,
            serverSide: false, // karena kita return array biasa
            ajax: {
                url: '/admin/dashboard/picker',
                dataSrc: 'data',
                data: function(d) {
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'picker_name'
                },
                {
                    data: 'picker_department'
                },
                {
                    data: 'total_barang',
                    className: 'text-center'
                },
                {
                    data: 'avg_duration_minutes',
                    className: 'text-center',
                    render: function(data) {
                        if (!data) return '-';

                        let hours = Math.floor(data / 60);
                        let minutes = Math.floor(data % 60);

                        if (hours > 0) {
                            return `${hours}j ${minutes}m`;
                        }
                        return `${minutes}m`;
                    }
                },
                {
                    data: 'total_error',
                    className: 'text-center',
                    render: function(data) {
                        let color = data > 0 ? 'danger' : 'success';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                },
                {
                    data: 'performance_score',
                    className: 'text-center',
                    render: function(data) {
                        let color = data >= 85 ? 'success' : data >= 70 ? 'warning' : 'danger';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                }
            ]
        });

        let tablePicking = $('#pickingTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '/admin/dashboard/picking',
                dataSrc: 'data',
                data: function(d) {
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'surat_jalan',
                    className: 'text-center'
                },
                {
                    data: 'picker_name',
                    className: 'text-center'
                },
                {
                    data: 'pic_start',
                    className: 'text-center'
                },
                {
                    data: 'started_at',
                    className: 'text-center',
                    render: function(data) {
                        return data ? moment.utc(data).local().format('DD MMM YYYY HH:mm') :
                            '-';
                    }
                },
                {
                    data: 'pic_end',
                    className: 'text-center'
                },
                {
                    data: 'finished_at',
                    className: 'text-center',
                    render: function(data) {
                        return data ? moment.utc(data).local().format('DD MMM YYYY HH:mm') :
                            '-';
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (!row.started_at || !row.finished_at) return '-';

                        let start = moment(row.started_at);
                        let end = moment(row.finished_at);

                        let duration = moment.duration(end.diff(start));

                        let hours = Math.floor(duration.asHours());
                        let minutes = duration.minutes();

                        return hours > 0 ?
                            `${hours}j ${minutes}m` :
                            `${minutes}m`;
                    }
                }
            ]
        });

        let tableBoarding = $('#boardingTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '/admin/dashboard/boarding',
                dataSrc: 'data',
                data: function(d) {
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'surat_jalan',
                    className: 'text-center'
                },
                {
                    data: 'outlet_name',
                    className: 'text-center'
                },
                {
                    data: 'box',
                    className: 'text-center'
                },
                {
                    data: 'koli',
                    className: 'text-center'
                },
                {
                    data: 'pic_boarding',
                    className: 'text-center'
                },

                {
                    data: 'started_at',
                    className: 'text-center',
                    render: function(data) {
                        return data ? moment(data).format('DD MMM YYYY HH:mm') : '-';
                    }
                },

                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (!row.started_at || !row.finished_at) return '-';

                        let start = moment(row.started_at);
                        let end = moment(row.finished_at);

                        let duration = moment.duration(end.diff(start));

                        let hours = Math.floor(duration.asHours());
                        let minutes = duration.minutes();

                        return hours > 0 ?
                            `${hours}j ${minutes}m` :
                            `${minutes}m`;
                    }
                }
            ]
        });

        let tableLoading = $('#loadingTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '/admin/dashboard/loading',
                dataSrc: 'data',
                data: function(d) {
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'surat_jalan',
                    className: 'text-center'
                },
                {
                    data: 'outlet_name',
                    className: 'text-center'
                },
                {
                    data: 'total_box',
                    className: 'text-center'
                },
                {
                    data: 'total_koli',
                    className: 'text-center'
                },
                {
                    data: 'driver',
                    className: 'text-center'
                },

                {
                    data: 'loading_start',
                    className: 'text-center',
                    render: function(data) {
                        return data ? moment.utc(data).local().format('DD MMM YYYY HH:mm') :
                            '-';
                    }
                },

                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (!row.loading_start || !row.loading_end) return '-';

                        let start = moment(row.loading_start);
                        let end = moment(row.loading_end);

                        let duration = moment.duration(end.diff(start));

                        let hours = Math.floor(duration.asHours());
                        let minutes = duration.minutes();

                        return hours > 0 ?
                            `${hours}j ${minutes}m` :
                            `${minutes}m`;
                    }
                }
            ]
        });

        let tableClaim = $('#claimTable').DataTable({
            processing: true,
            serverSide: false, // karena kita return array biasa
            ajax: {
                url: '/admin/dashboard/claim',
                dataSrc: 'data',
                data: function(d) {
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'picker_name'
                },
                {
                    data: 'picker_department'
                },
                {
                    data: 'total_barang',
                    className: 'text-center'
                },
                {
                    data: 'avg_duration_minutes',
                    className: 'text-center',
                    render: function(data) {
                        if (!data) return '-';

                        let hours = Math.floor(data / 60);
                        let minutes = Math.floor(data % 60);

                        if (hours > 0) {
                            return `${hours}j ${minutes}m`;
                        }
                        return `${minutes}m`;
                    }
                },
                {
                    data: 'total_error',
                    className: 'text-center',
                    render: function(data) {
                        let color = data > 0 ? 'danger' : 'success';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                },
                {
                    data: 'performance_score',
                    className: 'text-center',
                    render: function(data) {
                        let color = data >= 85 ? 'success' : data >= 70 ? 'warning' : 'danger';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                }
            ]
        });

        $('#refresh').on('click', function() {
            tablePicker.ajax.reload();
            tablePicking.ajax.reload();
            tableBoarding.ajax.reload();
            tableLoading.ajax.reload();
            loadDashboardSummary();
        });
    });
</script>
