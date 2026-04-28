<div class="row layout-top-spacing">

    {{-- CARD 1 --}}
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card bg-white shadow-sm border-0">
            <div class="card-body text-center">

                <i class="fa-solid fa-box fa-2x mb-2 text-primary"></i>
                <h5 class="card-title">Upcoming Picking</h5>
                <h3 class="fw-bold card-title">{{ $upComingTask }}</h3>
                <button class="btn btn-primary btn-icon mb-2 btn-rounded" id="scanBtn">
                    <i class="fa fa-camera"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-8  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Data Picking</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="pickingTable" class="table style-3 dt-table-hover">
                        <thead class="table-header">
                            <tr>
                                <th class="text-center col-no">No</th>
                                <th>PICKER</th>
                                <th class="text-center">SJ CODE</th>
                                <th class="text-center">STATUS</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal.picker-scanner />
<x-modal.picker-qty />

<script>
    $(document).ready(function() {
        let barcode = '';
        let lastTime = Date.now();

        $(document).on('keypress', function(e) {
            let currentTime = Date.now();

            // kalau jeda terlalu lama, reset (anggap bukan scanner)
            if (currentTime - lastTime > 100) {
                barcode = '';
            }

            lastTime = currentTime;

            if (e.which === 13) {
                $('#confirmQty').attr('data-id', barcode)
                $('#pickerQty').modal('show')
                barcode = '';
            } else {
                barcode += String.fromCharCode(e.which);
            }
        });

        $('#scanBtn').on('click', function(e) {
            // $('#scanBarcode').modal('show')
            $('#confirmQty').attr('data-id', 'HS01SJ/DC01-89103758')
            $('#pickerQty').modal('show')
        })

        $('#pickingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/picker/dashboard',
                type: 'GET'
            },
            dataSrc: function(json) {
                console.log(json.data);
                return json.data
            },
            columns: [{
                    data: null,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'picker.name',
                    name: 'picker'
                },
                {
                    data: 'barang.sjcode',
                    name: 'sjcode',
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: function(data, type, row) {

                        return `<span class="badge badge-success">${data.toUpperCase()}</span>`;

                    }
                },
            ],

            order: [
                [1, 'asc']
            ],

            responsive: true,
            autoWidth: false,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
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
