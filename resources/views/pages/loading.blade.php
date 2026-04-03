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
                            <i class="fa fa-truck-loading"></i>
                            Loading
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                            aria-controls="profile-tab-icon-pane" aria-selected="false">
                            <i class="fa fa-history"></i>
                            History
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
                                        <h4>Loading Operation</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form class="row" id="loadingForm">

                                    <div class="mb-3">
                                        <div class="row mb-3">
                                            <div class="col-lg-12">
                                                <x-form.select id="outlet" label="Tujuan Outlet" name="outletId"
                                                    :options="[null => 'Pilih Outlet'] + $outlets" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <x-form.input-btn placeholder="Masukkan No Surat Jalan" btnTxt="Scan"
                                                btnId="suratjalanBtn" name="codesj" type="basic"
                                                invalid="Harap Masukkan No Surat Jalan" label="Surat Jalan" id="sjcode"
                                                disabled="{{ false }}" toggle="modal" target="#scannerModal"
                                                value="tag1, tag2 autofocus" />
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <x-form.select id="driver" label="Pilih Driver" name="driver"
                                                    :options="[null => 'Pilih Driver'] + $drivers" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <x-form.select id="coDriver" label="Pilih Co-Driver" name="coDriver"
                                                    :options="[null => 'Pilih Co-Driver'] + $drivers" />
                                            </div>
                                        </div>
                                    </div>
                                    <table id="sjItems" class="table style-3 dt-table-hover mb-3">
                                        <thead class="table-header">
                                            <tr>
                                                <th class="text-center col-no">No</th>
                                                <th class="text-center col-no">No. Surat Jalan</th>
                                                <th class="text-center col-no">Box</th>
                                                <th class="text-center col-no">Koli</th>
                                                <th class="text-center col-no">Aging</th>
                                                <th class="text-center col-action">
                                                    <i class="fa-regular fa-square-check"></i>
                                                </th>
                                                <th class="text-center col-action">
                                                    <i class="fa fa-cog"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table id="titipItems" class="table style-3 dt-table-hover">
                                        <thead class="table-header">
                                            <tr>
                                                <th class="text-center col-no">No</th>
                                                <th class="text-center col-no">Nama Barang</th>
                                                <th class="text-center col-no">Jenis Barang</th>
                                                <th class="text-center col-no">Koli</th>
                                                <th class="text-center col-no">Aging</th>
                                                <th class="text-center col-action">
                                                    <i class="fa-regular fa-square-check"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <button type="submit" class="btn btn-primary mb-2 me-4 col-12 " id="loadigBtn">Mulai
                                        Loading</button>
                                </form>
                            </div>
                        </div>

                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-10 col-md-10col-sm-10 col-10">
                                        <h4>List Loading</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <table id="loadingTable" class="table style-3 dt-table-hover">
                                    <thead class="table-header">
                                        <tr>
                                            <th class="text-center col-no">No</th>
                                            <th class="text-center col-no">No. Surat Jalan</th>
                                            <th class="text-center col-no">Tujuan</th>
                                            <th class="text-center col-no">Driver</th>
                                            <th class="text-center col-no">Co-Driver</th>
                                            <th class="text-center col-no">Durasi</th>
                                            <th class="text-center col-action">
                                                <i class="fa fa-cog"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile-tab-icon-pane" role="tabpanel"
                        aria-labelledby="profile-tab-icon" tabindex="0">
                        <p class="mt-3">Aliquam at sem nunc. Maecenas tincidunt lacus justo, non ultrices mauris egestas
                            eu.
                            Vestibulum ut ipsum ac eros rutrum blandit in eget quam. Nullam et lobortis nunc. Nam sodales,
                            ante
                            sed sodales rhoncus, diam ipsum faucibus mauris, non interdum nisl lacus vel justo.</p>
                        <p>Sed imperdiet mi tincidunt mauris convallis, ut ullamcorper nunc interdum. Praesent maximus massa
                            eu
                            varius gravida. Nullam in malesuada enim. Morbi commodo pellentesque velit sodales pretium.
                            Mauris
                            scelerisque augue vel est pulvinar laoreet.</p>
                    </div>
                </div>
            </div>
        </div>
        <x-modal.scan-modal inputId="sjcode" />
    </div>

    <script>
        $(document).ready(function() {
            moment.locale('id')
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


            let tableSj = $('#sjItems').DataTable({
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'barang.sjcode',
                        defaultContent: '-'
                    },
                    {
                        className: 'text-center',
                        data: 'qty',
                        defaultContent: '-'
                    },
                    {
                        className: 'text-center',
                        data: 'koli',
                        defaultContent: '-'
                    },
                    {
                        className: 'text-center',
                        data: 'boarding_start',
                        render: function(data) {
                            return data ? moment.utc(data).local().fromNow() : '-';
                        }
                    },
                    {
                        className: 'text-center',
                        data: null,
                        render: function(data) {
                            return `
            <input 
                type="checkbox"
                class="form-check-input sj-select"
                name="selectedSj[]"
                value="${data.id}"
            >
        `;
                        }
                    },
                    {
                        className: 'text-center',
                        data: null,
                        render: function(data, row) {
                            return `<div class="dropdown">
                                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                        </a>
    
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                            <a class="dropdown-item delete-btn" data-id="${data.barang.id}">Split</a>
                                                        </div>
                                                    </div>`;
                        }
                    }
                ]
            });

            $('#sjItems tbody').on('dblclick', 'td', function() {
                let cell = tableSj.cell(this);
                let columnIndex = cell.index().column;

                let field = tableSj.settings().init().columns[columnIndex].data;

                // hanya boleh koli & box
                if (!['koli', 'qty'].includes(field)) return;

                let oldValue = cell.data();
                let td = $(this);

                if (td.find('input').length) return;

                td.html(`<input type="text" value="${oldValue}" class="form-control form-control-sm" />`);

                td.find('input').focus().select();
            });

            $('#sjItems tbody').on('blur', 'input', function() {
                saveData($(this));
            });

            $('#sjItems tbody').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    saveData($(this));
                }
            });

            let tableTitip = $('#titipItems').DataTable({
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        className: 'text-center',
                        data: 'barang.nama_barang',
                        defaultContent: '-'
                    },
                    {
                        className: 'text-center',
                        data: 'barang.jenis_barang.name',
                        defaultContent: '-'
                    },
                    {
                        className: 'text-center',
                        data: 'koli',
                        defaultContent: '-'
                    },
                    {
                        className: 'text-center',
                        data: 'boarding_start',
                        render: function(data) {
                            return data ? moment.utc(data).local().fromNow() : '-';
                        }
                    },
                    {
                        className: 'text-center',
                        data: null,
                        render: function(data) {
                            return `
            <input 
                type="checkbox"
                class="form-check-input sj-select"
                name="selectedSj[]"
                value="${data.id}"
            >
        `;
                        }
                    }
                ]
            });

            function saveData(input) {
                let td = input.closest('td');
                let tr = input.closest('tr');

                let newValue = input.val();
                let cell = tableSj.cell(td);
                let columnIndex = cell.index().column;

                let rowData = tableSj.row(tr).data();
                let id = rowData.barang.id;

                // mapping column ke field database
                let field = tableSj.settings().init().columns[columnIndex].data;
                console.log(field, newValue, id)
                $.ajax({
                    url: `/loading/update/${id}`,
                    method: 'PATCH',
                    data: {
                        field: field,
                        value: newValue
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        cell.data(newValue).draw();

                        // efek dikit biar manis 😏
                        td.css('background', '#d4edda');
                        setTimeout(() => td.css('background', ''), 500);
                        toast.fire({
                            icon: 'success',
                            title: 'Data updated successfully'
                        });
                    },
                    error: function(xhr, status, error) {
                        toast.fire({
                            icon: 'error',
                            title: 'Failed to update data'
                        });
                        cell.data(rowData[field]).draw();
                    }
                });

            }

            $('#outlet').on('change', function() {

                let outletId = $(this).val();
                if (outletId) {
                    $.ajax({
                        url: `/loading/items/${outletId}`,
                        type: 'GET',
                        success: function(data) {
                            console.log(data);
                            tableSj.clear().rows.add(data.data.reguler).draw();
                            tableTitip.clear().rows.add(data.data.titip).draw();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching Surat Jalan:', error);
                        }
                    });
                } else {
                    tableSj.clear().draw();
                    tableTitip.clear().draw();
                }
            });

            // init DataTable sekali
            let table = $('#loadingTable').DataTable({
                processing: true,
                ajax: {
                    url: '/loading/items',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json;
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
                        data: 'surat_jalan',
                        name: 'Loading Code'
                    },
                    {
                        data: 'outlet.name',
                        name: 'Tujuan'
                    },
                    {
                        data: 'driver.name',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'coDriver.name',
                        className: 'text-center',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'loading_start',
                        className: 'text-center',
                        render: function(data) {
                            return data ? moment.utc(data).local().fromNow() : '-';
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
                                                            <a class="dropdown-item delete-btn" data-id="${row.id}">View</a>
                                                            <a class="dropdown-item update-picker">Update</a>
                                                        </div>
                                                    </div>`;
                        }
                    }
                ]
            });

            $('#loadingForm').on('submit', function(e) {
                e.preventDefault();
                let selectedSj = [];
                $('.sj-select:checked').each(function() {
                    selectedSj.push($(this).val());
                });
                let driverId = $('#driver').val();
                let coDriverId = $('#coDriver').val();
                let outletId = $('#outlet').val();
                let sjCode = $('#sjcode').val();

                if (selectedSj.length === 0) {
                    alert('Pilih minimal satu Surat Jalan untuk loading.');
                    return;
                }
                if (!driverId) {
                    alert('Pilih driver untuk loading.');
                    return;
                }
                if (!sjCode) {
                    alert('Masukkan No Surat Jalan.');
                    return;
                }


                $.ajax({
                    url: '/loading',
                    type: 'POST',
                    data: {
                        sjIds: selectedSj,
                        driverId: driverId,
                        coDriverId: coDriverId,
                        outletId: outletId,
                        sjCode: sjCode,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toast.fire({
                            icon: 'success',
                            title: 'Loading operation started successfully'
                        });
                        $('#outlet').trigger('change'); // refresh data items
                        table.ajax.reload(); // refresh data table
                    },
                    error: function(xhr, status, error) {
                        toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message ||
                                'Failed to start loading operation'
                        });
                        console.log(xhr.responseJSON.errors);
                    }
                });

            });

        });
    </script>
@endsection
