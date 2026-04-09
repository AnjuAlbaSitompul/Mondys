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
                                    <button type="submit" class="btn btn-primary mb-2 me-4 col-12 " id="loadingBtn">Tambah
                                        Loading</button>
                                    <button type="button" class="btn btn-primary mb-2 me-4 col-12 " id="updateLoading"
                                        style="display: none;">Update
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
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-10 col-md-10col-sm-10 col-10">
                                        <h4>History Loading</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">

                                <table id="historyTable" class="table style-3 dt-table-hover mb-3">
                                    <thead class="table-header">
                                        <tr>
                                            <th class="text-center col-no">No</th>
                                            <th class="text-center col-no">No. Surat Jalan</th>
                                            <th class="text-center col-no">Tanggal Kirim</th>
                                            <th class="text-center col-no">Outlet Tujuan</th>
                                            <th class="text-center col-no">Driver</th>
                                            <th class="text-center col-action">
                                                <i class="fa-regular fa-square-check"></i>
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
        <x-modal.scan-modal inputId="sjcode" />
        <x-modal.split-modal />
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
                ${data.checked ? 'checked' : ''}
            >
        `;
                        }
                    },
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

                td.html(
                    `<input type="number" value="${oldValue}" name="${field}" class="form-control form-control-sm text-edit" max="${oldValue}"/>`
                );

                td.find('input').focus().select();
            });



            // $(document).on('input', '.text-edit', function(val) {
            //     let max = Number($(this).attr('max'));
            //     let value = Number($(this).val());

            //     if (value > max) {
            //         $(this).val(max);
            //     }

            //     if (value < 0) {
            //         $(this).val(0);
            //     }
            // })




            $('#sjItems tbody').on('blur', 'input', function() {

                saveData($(this), tableSj);
            });

            $('#sjItems tbody').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    saveData($(this), tableSj);
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
                ${data.checked ? 'checked' : ''}
            >
        `;
                        }
                    },
                ]
            });

            $('#titipItems tbody').on('dblclick', 'td', function() {
                let cell = tableTitip.cell(this);
                let columnIndex = cell.index().column;

                let field = tableTitip.settings().init().columns[columnIndex].data;

                // hanya boleh koli & box
                if (!['koli'].includes(field)) return;

                let oldValue = cell.data();
                let td = $(this);

                if (td.find('input').length) return;

                td.html(
                    `<input type="number" value="${oldValue}" name="${field}" class="form-control form-control-sm text-edit"/>`
                );

                td.find('input').focus().select();
            });

            $('#titipItems tbody').on('blur', 'input', function() {

                saveData($(this), tableTitip);
            });

            $('#titipItems tbody').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    saveData($(this), tableTitip);
                }
            });


            function saveData(input, chosenTable) {
                let td = input.closest('td');
                let tr = input.closest('tr');

                let newValue = input.val();
                let cell = chosenTable.cell(td);
                let columnIndex = cell.index().column;

                let rowData = chosenTable.row(tr).data();
                let id = rowData.barang.id;

                // mapping column ke field database
                let field = chosenTable.settings().init().columns[columnIndex].data;
                if (!['koli', 'qty'].includes(field)) return;
                cell.data(newValue).draw();
                td.css('background', '#d4edda');
                setTimeout(() => td.css('background', ''), 500);

            }

            $('#outlet').on('change', function() {

                let outletId = $(this).val();
                if (outletId) {
                    $.ajax({
                        url: `/loading/items/${outletId}`,
                        type: 'GET',
                        success: function(data) {
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
                        data: 'co_driver.name',
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
                                                            <a class="dropdown-item update-loading" data-id="${data.id}">Update</a>
                                                            <a class="dropdown-item print-loading" data-id="${data.id}">Print</a>
                                                        </div>
                                                    </div>`;
                        }
                    }
                ]
            });
            $(document).on('click', '.print-loading', function() {
                let id = $(this).data('id');
                window.open(`/loading/print/${id}`)
            })

            $(document).on('click', '.update-loading', function() {
                let id = $(this).data('id');


                $.ajax({
                    url: `/loading/${id}`,
                    type: 'GET',
                    success: function(res) {
                        tableTitip.clear();
                        tableSj.clear();

                        $('#driver').val(res.loading.driver.id)
                        $('#coDriver').val(res.loading.co_driver.id)
                        $('#outlet').val(res.loading.outlet_id)
                        $('#sjcode').val(res.loading.surat_jalan)
                        let titip = res.availableBoarding.titip || [];
                        let reguler = res.availableBoarding.reguler || [];
                        let loaded = res.loadedItems || [];

                        // 🔥 mapping loadedItems → boarding + override koli & qty
                        let mappedLoaded = loaded.map(item => {
                            return {
                                ...item.boarding_list, // ambil data boarding
                                koli: item.koli,
                                qty: item.box,
                                checked: true // 🔥 tandai sudah dipilih
                            };
                        });

                        // 🔥 masukkan ke masing-masing table
                        mappedLoaded.forEach(item => {
                            let type = item?.barang?.type;

                            if (type === 'TITIP') {
                                titip.push(item);
                            } else if (type === 'REGULER') {
                                reguler.push(item);
                            }
                        });

                        // 🔹 inject ke table
                        tableTitip.rows.add(titip).draw();
                        tableSj.rows.add(reguler).draw();
                        $('#updateLoading').attr('data-id', res.loading.id)
                        $('#updateLoading').show();
                        $('#loadingBtn').hide();

                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal ambil data 😢');
                    }
                });
            });

            $('#updateLoading').on('click', function(e) {
                let selectedItems = [];
                let id = $(this).data('id')

                function collectSelected(table) {
                    table.rows().every(function() {
                        let rowNode = this.node();
                        let isChecked = $(rowNode).find('.sj-select').is(':checked');

                        if (isChecked) {
                            let data = this.data();

                            selectedItems.push({
                                id: data.id,
                                koli: data.koli,
                                qty: data.qty
                            });
                        }
                    });
                }

                // ambil dari semua table
                collectSelected(tableSj);
                collectSelected(tableTitip);


                let driverId = $('#driver').val();
                let coDriverId = $('#coDriver').val();
                let outletId = $('#outlet').val();
                let sjCode = $('#sjcode').val();

                if (selectedItems.length === 0) {
                    alert('Pilih minimal satu Item untuk loading.');
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
                    url: `/loading/update/${id}`,
                    type: 'PATCH',
                    data: {
                        items: selectedItems,
                        driverId: driverId,
                        coDriverId: coDriverId,
                        outletId: outletId,
                        sjCode: sjCode,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Loading operation updated successfully'
                        });
                        $('#outlet').trigger('change'); // refresh data items
                        table.ajax.reload(); // refresh data table
                    },
                    error: function(xhr, status, error) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message ||
                                'Failed to update loading operation'
                        });
                        console.log(xhr.responseJSON.errors);
                    }
                });
            })
            $('#loadingForm').on('submit', function(e) {
                e.preventDefault();
                let selectedItems = [];

                function collectSelected(table) {
                    table.rows().every(function() {
                        let rowNode = this.node();
                        let isChecked = $(rowNode).find('.sj-select').is(':checked');

                        if (isChecked) {
                            let data = this.data();

                            selectedItems.push({
                                id: data.id,
                                koli: data.koli,
                                qty: data.qty
                            });
                        }
                    });
                }

                // ambil dari semua table
                collectSelected(tableSj);
                collectSelected(tableTitip);


                let driverId = $('#driver').val();
                let coDriverId = $('#coDriver').val();
                let outletId = $('#outlet').val();
                let sjCode = $('#sjcode').val();

                if (selectedItems.length === 0) {
                    alert('Pilih minimal satu Item untuk loading.');
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
                        items: selectedItems,
                        driverId: driverId,
                        coDriverId: coDriverId,
                        outletId: outletId,
                        sjCode: sjCode,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Loading operation started successfully'
                        });
                        $('#outlet').trigger('change'); // refresh data items
                        $('#updateLoading').hide();
                        $('#loadingBtn').show();

                        table.ajax.reload(); // refresh data table
                    },
                    error: function(xhr, status, error) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message ||
                                'Failed to start loading operation'
                        });
                        console.log(xhr.responseJSON.errors);
                    }
                });

            });

        });

        let historyTable = $('#historyTable').DataTable({
            processing: true,
            ajax: {
                url: '/loading/history',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data
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
                    data: 'created_at',
                    className: 'text-center',
                    render: function(data) {
                        if (!data) return '-';
                        let date = new Date(data);
                        return date.toLocaleString('id-ID');
                    }
                },
                {
                    data: 'outlet',
                    className: 'text-center',
                    render: function(data) {
                        return data?.codeOutlet ?? '-';
                    }
                },
                {
                    data: 'driver',
                    className: 'text-center',
                    render: function(data, type, row) {
                        let driver = row.driver?.name ?? '-';
                        let coDriver = row.co_driver?.name ?? '';
                        return coDriver ? `${driver} / ${coDriver}` : driver;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function(data) {
                        return `
                    <button class="btn btn-sm btn-primary print-loading" data-id="${data}">
                        Print
                    </button>
                `;
                    }
                }
            ]
        });
    </script>
@endsection
