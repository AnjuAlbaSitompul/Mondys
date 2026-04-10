<div class="row layout-top-spacing">

    {{-- CARD 1 --}}
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card bg-white shadow-sm border-0">
            <div class="card-body text-center">

                <i class="fa-solid fa-box fa-2x mb-2 text-primary"></i>
                <h5 class="card-title">Upcoming Delivering</h5>
                <h3 class="fw-bold card-title">{{ $totalDelivery }}</h3>
                <button class="btn btn-primary mb-2" id="scanBtn" {{ $isDelivering ? 'style=display:none' : '' }}>
                    Scan to Start
                </button>

                <button class="btn btn-primary mb-2" id="clockInBtn"
                    data-id="{{ $isDelivering ? $isDelivering->id : null }}"
                    {{ !$isDelivering ? 'style=display:none' : '' }}>
                    Clock In
                </button>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-8  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Data Delivering</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="pickingTable" class="table style-3 dt-table-hover">
                        <thead class="table-header">
                            <tr>
                                <th class="text-center col-no">No</th>
                                <th>No Surat Jalan</th>
                                <th class="text-center">Tujuan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal.driver-scanner />
<script>
    $(document).ready(function() {

        $('#scanBtn').on('click', function() {
            $('#scanBarcodeDriver').modal('show')
        });
        $('#pickingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/driver/dashboard',
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
                    data: 'surat_jalan',
                    name: 'picker'
                },
                {
                    data: 'outlet.codeOutlet',
                    name: 'sjcode',
                    className: 'text-center'
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

        $('#clockInBtn').on('click', function() {
            let id = $(this).data('id')
            alert(id)
            window.location.href = "{{ url('/deliver/camera/' . $isDelivering->id) }}"
        });
    })
</script>
