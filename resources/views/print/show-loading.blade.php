@extends('layouts.app')
@section('title', 'Preview Surat Tugas')

@section('loader')
    @include('partials.loader')
@endsection

@section('content')
    <style>
        /* =========================================================
                       1. STYLING UNTUK PREVIEW UI (Tampilan Sekeliling)
                       ========================================================= */
        .preview-wrapper {
            background-color: #f8f9fa;
            padding: 20px 0;
            min-height: 100vh;
        }

        .paper-preview {
            background-color: #ffffff;
            max-width: 210mm;
            /* Lebar maksimal A4 */
            min-height: 297mm;
            /* Tinggi minimal A4 */
            margin: 0 auto;
            padding: 10mm;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow-x: auto;
        }

        /* =========================================================
                       2. MODE PRINT RESTRICT (Hanya Surat yang Tercetak)
                       ========================================================= */
        @media print {

            /* Sembunyikan semua elemen di layar secara aman */
            body * {
                visibility: hidden;
            }

            /* Tampilkan KEMBALI HANYA area surat beserta anak-anaknya */
            #printArea,
            #printArea * {
                visibility: visible;
                color: #000 !important;
                /* Paksa teks warna hitam (anti dark-mode) */
            }

            /* Tarik surat paksa ke ujung atas-kiri agar tidak tergeser oleh elemen navbar yang disembunyikan */
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            /* Hilangkan bingkai/shadow wrapper saat di kertas */
            .paper-preview {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }

            /* Pastikan background alert/note dan barcode bisa di-print */
            #printArea .note,
            #printArea .barcode {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }

        /* =========================================================
                       3. STYLING SURAT ASLI ANDA (Tidak Diubah)
                       ========================================================= */
        #printArea {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: black;
        }

        #printArea .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #printArea .title {
            font-weight: bold;
            font-size: 16px;
        }

        #printArea .barcode {
            text-align: right;
        }

        #printArea .line {
            border-bottom: 2px solid black;
            margin: 5px 0 10px 0;
        }

        #printArea .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        #printArea .note {
            background: #fff3cd;
            padding: 8px;
            border: 1px solid #ccc;
            font-size: 11px;
            margin-bottom: 10px;
        }

        #printArea table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        #printArea table,
        #printArea th,
        #printArea td {
            border: 1px solid black;
        }

        #printArea th,
        #printArea td {
            padding: 4px;
            text-align: center;
        }

        #printArea .text-left {
            text-align: left;
        }

        #printArea .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        #printArea .signature {
            width: 30%;
        }

        #printArea .signature div {
            margin-top: 40px;
            border-top: 1px solid black;
        }
    </style>

    <div class="preview-wrapper">
        <div class="container">

            <div
                class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white shadow-sm rounded border d-print-none">
                <div>
                    <h5 class="mb-0 fw-bold">Preview Surat Pengantaran</h5>
                    <span class="text-muted small">ID Loading: {{ $loading->id }}</span>
                </div>
                <div>
                    <button class="btn btn-outline-secondary me-2" onclick="history.back()">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                    <button class="btn btn-primary" id="btnPrint">
                        <i class="bi bi-printer-fill"></i> Print Surat Jalan
                    </button>
                </div>
            </div>

            <div class="paper-preview">

                <div id="printArea">
                    <div class="header">
                        <div>
                            <div class="title">SURAT PENGANTARAN BARANG</div>
                            <div>{{ $loading->surat_jalan }}</div>
                        </div>

                        <div class="barcode">
                            {!! DNS1D::getBarcodeHTML($loading->surat_jalan, 'C128') !!}
                        </div>
                    </div>

                    <div class="line"></div>

                    <div class="info">
                        {{-- <div>Dari: {{ $loading->creator->location->code ?? '-' }}</div> --}}
                        <div>Dari: DC01</div>
                        <div>Tujuan: {{ $loading->outlet->codeOutlet ?? '-' }}</div>
                        <div>Tiba: __________</div>
                    </div>

                    <div class="note">
                        *Note: Sebelum Driver Tiba Pihak Toko Wajib Menyiapkan Box Container, Kardus & Barang Mutasi Antar
                        Cabang
                        yang sudah dibuat Surat Jalan. Apabila
                        Pihak Toko Belum Menyelesaikan Surat Jalan, Maka Diberi Waktu 30 Menit Agar Driver Dapat Menunggu.
                        Jika
                        Driver Menolak/Mengabaikan Untuk
                        Mengangkut Instruksi Diatas Maka Driver Dikenai Sanksi S/P
                    </div>

                    <b>Rekap</b>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Barang</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekapJenis as $nama => $qty)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ $nama }}</td>
                                    <td>{{ $qty }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="2" class="text-left"><b>Total</b></td>
                                <td><b>{{ $rekapJenis->sum() }}</b></td>
                            </tr>
                        </tbody>
                    </table>

                    <b>Titipan</b>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Type Barang</th>
                                <th>Koli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalKoli = 0;
                            @endphp

                            @foreach ($titipItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-left">
                                        {{ $item->boardingList?->barang?->nama_barang ?? '-' }}
                                    </td>
                                    <td class="text-left">
                                        {{ $item->boardingList?->barang?->jenisBarang?->name ?? '-' }}
                                    </td>
                                    <td>{{ $item->koli }}</td>
                                </tr>

                                @php
                                    $totalKoli += $item->koli ?? 0;
                                @endphp
                            @endforeach

                            <tr>
                                <td colspan="3" class="text-left"><b>Total Titipan</b></td>
                                <td><b>{{ $totalKoli }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="font-size: x-small">*1 Diluar dari SI Warehouse *2 Jika ada Selisih/Rusak Mohon Lapor untuk
                        foto
                        Stiker SI & SI Affair
                    </div>

                    <b>Detail</b>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No SJ</th>
                                <th>Keterangan</th>
                                <th>Box</th>
                                <th>Koli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $regularItems = $loading->details->filter(fn($d) => $d->boardingList?->barang?->type === 'REGULER');

                                $totalBox = 0;
                                $totalRegulerKoli = 0;
                            @endphp

                            @foreach ($regularItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-left">
                                        {{ $item->boardingList?->barang?->sjcode ?? '-' }}
                                    </td>
                                    <td>
                                    </td>
                                    <td>{{ $item->box }}</td>
                                    <td>{{ $item->koli }}</td>
                                </tr>

                                @php
                                    $totalBox += $item->box ?? 0;
                                    $totalRegulerKoli += $item->koli ?? 0;
                                @endphp
                            @endforeach

                            <tr>
                                <td colspan="3" class="text-left"><b>Total Detail</b></td>
                                <td><b>{{ $totalBox }}</b></td>
                                <td><b>{{ $totalRegulerKoli }}</b></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="footer">
                        <div class="signature">
                            Menyetujui<br>Good Delivery
                            <div>{{ $loading->creator->name ?? '' }}</div>
                        </div>

                        <div class="signature">
                            Menyetujui<br>Driver
                            <div>{{ $loading->driver->name ?? '' }} / {{ $loading->coDriver->name ?? '' }}</div>
                        </div>

                        <div class="signature">
                            Penerima<br>Good Receiver
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Eksekusi fungsi print browser saat tombol diklik
            $('#btnPrint').on('click', function () {
                window.print();
            });
        });
    </script>
@endsection