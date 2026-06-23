@extends('layouts.app')
@section('title', 'Daftar Surat Jalan')

@section('loader')
    @include('partials.loader')
@endsection

@section('content')
    <style>
        /* Timeline Styles */
        .tracking-timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline-item {
            border-left: 2px solid #0d6efd;
            padding-left: 20px;
            padding-bottom: 20px;
            position: relative;
        }

        .timeline-item:last-child {
            border-left: 2px solid transparent;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 14px;
            height: 14px;
            background-color: #0d6efd;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* Dark Mode Adjustments */
        [data-bs-theme="dark"] .timeline-item {
            border-left-color: #6ea8fe;
        }

        [data-bs-theme="dark"] .timeline-item::before {
            background-color: #6ea8fe;
            border-color: #212529;
        }

        [data-bs-theme="dark"] .card {
            background-color: #2b3035;
            border-color: #495057;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Surat Jalan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4 align-items-end">
                    <div class="col-md-3">
                        <label for="dateFrom" class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" class="form-control" id="dateFrom"
                            value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    </div>
                    <div class="col-md-3">
                        <label for="dateTo" class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="dateTo"
                            value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" id="btnFilter">
                            <i class="bi bi-search"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
                <hr>
                <table class="table table-hover align-middle dt-responsive nowrap" id="sjdatatable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Surat Jalan</th>
                            <th>Outlet Tujuan</th>
                            <th>Driver</th>
                            <th>Status Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="detailModalLabel">
                        Tracking Detail: <span id="modalSjCode" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tracking-timeline">

                        <div class="timeline-item">
                            <h6 class="fw-bold mb-1">1. Picking Process</h6>
                            <div class="small text-muted">
                                <p class="mb-0"><strong>Petugas Pick:</strong> <span id="detPicker">Memuat...</span></p>
                                <p class="mb-0"><strong>Waktu:</strong> <span id="detPickTime">Memuat...</span></p>
                                <p class="mb-0 text-primary"><strong>Durasi:</strong> <span id="detPickDur">Memuat...</span>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <h6 class="fw-bold mb-1">2. Boarding Area</h6>
                            <div class="small text-muted">
                                <p class="mb-0"><strong>Waktu:</strong> <span id="detBoardTime">Memuat...</span></p>
                                <p class="mb-0 text-primary"><strong>Durasi:</strong> <span
                                        id="detBoardDur">Memuat...</span></p>
                                <p class="mb-0"><strong>Sisa Stock di Boarding:</strong> <span
                                        id="detBoardStock">Memuat...</span></p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <h6 class="fw-bold mb-1">3. Loading ke Kendaraan</h6>
                            <div class="small text-muted">
                                <p class="mb-0"><strong>Driver:</strong> <span id="detDriver">Memuat...</span> |
                                    <strong>Co-Driver:</strong> <span id="detCoDriver">Memuat...</span>
                                </p>
                                <p class="mb-0"><strong>Waktu:</strong> <span id="detLoadTime">Memuat...</span></p>
                                <p class="mb-0 text-primary"><strong>Durasi:</strong> <span id="detLoadDur">Memuat...</span>
                                </p>
                                <button id="detSuratJalanLink" class="btn btn-sm btn-outline-secondary mt-2">
                                    <i class="bi bi-file-earmark-text"></i> Tampilkan File Surat Jalan
                                </button>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <h6 class="fw-bold mb-1">4. Delivering (Pengiriman)</h6>
                            <div class="small text-muted">
                                <p class="mb-0"><strong>Mulai Berangkat (Start):</strong> <span
                                        id="detDelStart">Memuat...</span></p>
                                <p class="mb-0"><strong>Tiba (Clock In) - Selesai (Clock Out):</strong> <span
                                        id="detDelClock">Memuat...</span></p>
                                <p class="mb-0 text-primary"><strong>Durasi Perjalanan:</strong> <span
                                        id="detDelDur">Memuat...</span></p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <h6 class="fw-bold mb-1 text-danger">5. Claim & Reports</h6>
                            <div class="small text-muted bg-light p-2 rounded border" data-bs-theme="light">
                                <span id="detClaim" class="fst-italic text-dark">Tidak ada data claim / Checking...</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables
            let table = $('#sjdatatable').DataTable({
                processing: true,
                serverSide: false, // Karena kita pakai JSON utuh
                responsive: true,
                ajax: {
                    url: "{{ route('surat-jalan.data') }}",
                    type: 'GET',
                    data: function (d) {
                        // Kirim data tanggal ke Controller
                        d.date_from = $('#dateFrom').val();
                        d.date_to = $('#dateTo').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'surat_jalan', name: 'surat_jalan', className: 'fw-bold' },
                    { data: 'outlet', name: 'outlet' },
                    { data: 'driver', name: 'driver' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // 2. Event Listener untuk Tombol Filter
            $('#btnFilter').on('click', function () {
                // Me-reload ulang data di tabel dengan parameter tanggal terbaru
                table.ajax.reload();
            });

            // Gunakan Event Delegation pada `tbody` agar tombol pada DataTables yang baru dirender tetap berfungsi
            $('#sjdatatable tbody').on('click', '.btn-detail', function () {
                let sjCode = $(this).data('sj');

                $('#modalSjCode').text(sjCode);
                $('#detailModal').modal('show');
                $('.timeline-item span[id^="det"]').text('Memuat data...');

                $.ajax({
                    url: `/surat-jalan-detail`,
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        sjCode: sjCode
                    },
                    success: function (res) {
                        // Mapping Data Picking
                        $('#detPicker').text(res.picking.picker);
                        $('#detPickTime').html(res.picking.time);
                        $('#detPickDur').text(res.picking.duration);

                        // Mapping Data Boarding
                        $('#detBoardTime').html(res.boarding.time);
                        $('#detBoardDur').text(res.boarding.duration);
                        $('#detBoardStock').text(res.boarding.stock);

                        // Mapping Data Loading
                        $('#detDriver').text(res.loading.driver);
                        $('#detCoDriver').text(res.loading.co_driver);
                        $('#detLoadTime').html(res.loading.time);
                        $('#detLoadDur').text(res.loading.duration);

                        // Logika Tombol Tampilkan Surat Jalan
                        let btnSurat = $('#detSuratJalanLink');
                        if (res.loading.id) {
                            btnSurat.show();
                            // Hapus event click lama agar tidak menumpuk, lalu tambahkan yang baru
                            btnSurat.off('click').on('click', function () {
                                // Membuka route page loading print di tab baru
                                window.open(`/loading/preview/${res.loading.id}`, '_blank');
                            });
                        } else {
                            btnSurat.hide(); // Sembunyikan tombol jika belum ada data loading
                        }

                        // Mapping Data Delivering
                        $('#detDelStart').text(res.delivering.start);
                        $('#detDelClock').html(res.delivering.clock);
                        $('#detDelDur').text(res.delivering.duration);

                        // Mapping Data Claim
                        $('#detClaim').html(`<span class="text-success">${res.claim}</span>`);
                    },
                    error: function (xhr) {
                        if (xhr.status === 404) {
                            alert('Data detail surat jalan tidak ditemukan.');
                        } else {
                            alert('Terjadi kesalahan saat memuat data dari server.');
                        }
                        // $('#detailModal').modal('hide');
                    }
                });
            });
        });
    </script>
@endsection