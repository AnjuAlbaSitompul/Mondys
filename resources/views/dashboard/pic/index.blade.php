<div class="row layout-top-spacing">

    {{-- CARD 1 --}}
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card bg-white shadow-sm border-0">
            <div class="card-body text-center">

                <i class="fa-solid fa-box fa-2x mb-2 text-primary"></i>
                <h5 class="card-title">Upcoming Delivering</h5>
                <h3 class="fw-bold card-title upcoming">{{ $totalDelivering }}</h3>

                @php
                    $clockIn = $hasClockIn->first();
                @endphp

                <button class="btn btn-primary mb-2" id="clockOutBtn" data-id="{{ $clockIn->id ?? '' }}"
                    @if ($hasClockIn->isEmpty()) style="display:none" @endif>
                    Clock Out Driver
                </button>
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
                    <table id="picTable" class="table style-3 dt-table-hover">
                        <thead class="table-header">
                            <tr>
                                <th class="text-center col-no">No</th>
                                <th>No Surat Jalan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">
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
<x-modal.modal-pic />
<x-modal.rating-modal />
<x-modal.claim-modal />
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
        })

        let detailTable = $('#detailTable').DataTable({
            processing: true,
            searching: false,
            paging: false,
            info: false,
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'boarding_list.barang.sjcode',
                    defaultContent: '-'
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
                    data: 'boarding_list.barang.type',
                    className: 'text-center',
                    defaultContent: '-'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                    <a class="dropdown-item claim-btn" data-id="${row.boarding_list.barang.id}" data-sj="${row.boarding_list.barang.sjcode}">Claim</a>

                </div>
            </div>
        `;
                    }
                }
            ]
        });



        let picTable = $('#picTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/pic/dashboard',
                type: 'GET'
            },
            dataSrc: function(json) {
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
                    data: 'loading.surat_jalan',
                    name: 'picker'
                },
                {
                    data: 'clock_out',
                    name: 'status',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data) {
                            return `<span class="badge badge-success">Selesai</span>`;
                        } else {
                            return `<span class="badge badge-success">Upcoming</span>`;

                        }

                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                    <a class="dropdown-item detail-btn" data-id="${row.id}">View Detail</a>

                </div>
            </div>
        `;
                    }
                }
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


        $('#clockOutBtn').on('click', function() {
            let id = $(this).data('id'); // pastikan ada data-id di button

            $.ajax({
                url: `/deliver/clock-out/${id}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $('#submitRating').attr('data-id', res.data.driver_id)
                    $('#ratingModal').modal('show');
                    Toast.fire({
                        icon: 'success',
                        title: "Driver Sudah Clock Out"
                    });
                    picTable.ajax.reload()
                    $('#clockOutBtn').hide()
                },
                error: function(xhr) {
                    let res = xhr.responseJSON;

                    Toast.fire({
                        icon: 'error',
                        title: res?.message ?? "Terjadi Kesalahan"
                    })
                }
            });
        });

        $(document).on('click', '.detail-btn', function() {
            let id = $(this).data('id');

            $.ajax({
                url: `/pic/detail/${id}`,
                type: 'GET',
                success: function(res) {
                    console.log(res)
                    detailTable.clear();
                    detailTable.rows.add(res.data);
                    detailTable.draw();
                    $('#detailModal').modal('show');
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message ?? 'Terjadi Kesalahan';

                    Toast.fire({
                        icon: 'error',
                        title: message
                    })
                }
            });
        });

        $(document).on('click', '.claim-btn', function() {
            let id = $(this).data('id');
            let sj = $(this).data('sj');
            $('#submitClaim').attr('data-id', id);
            $('.textClaim').text(`Claim Barang Dengan SJ ` + sj);
            $('#detailModal').modal('hide');
            $('#claimModal').modal('show');
        })


    })
</script>
