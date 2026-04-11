<!DOCTYPE html>
<html>

<head>
    <title>Surat Pengantaran Barang</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
        }

        .barcode {
            text-align: right;
        }

        .line {
            border-bottom: 2px solid black;
            margin: 5px 0 10px 0;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .note {
            background: #fff3cd;
            padding: 8px;
            border: 1px solid #ccc;
            font-size: 11px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 4px;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .signature {
            width: 30%;
        }

        .signature div {
            margin-top: 40px;
            border-top: 1px solid black;
        }

        @media print {
            .barcode {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            svg,
            img {
                display: block !important;
                visibility: visible !important;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">

        <!-- HEADER -->
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
        <!-- INFO -->
        <div class="info">
            <div>Dari: {{ $loading->creator->location->code ?? '-' }}</div>

            <div>Tujuan: {{ $loading->outlet->codeOutlet ?? '-' }}</div>
            <div>Tiba: __________</div>
        </div>

        <!-- NOTE -->
        <div class="note">
            *Note: Sebelum Driver Tiba Pihak Toko Wajib Menyiapkan Box Container, Kardus & Barang Mutasi Antar Cabang
            yang sudah dibuat Surat Jalan. Apabila
            Pihak Toko Belum Menyelesaikan Surat Jalan, Maka Diberi Waktu 30 Menit Agar Driver Dapat Menunggu. Jika
            Driver Menolak/Mengabaikan Untuk
            Mengangkut Instruksi Diatas Maka Driver Dikenai Sanksi S/P
        </div>

        <!-- REKAP -->
        <h4>Rekap</h4>

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

        {{-- ================= TITIP ================= --}}
        <h4>Titipan</h4>
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
        <di style="font-size: x-small">*1 Diluar dari SI Warehouse *2 Jika ada Selisih/Rusak Mohon Lapor untuk foto
            Stiker SI & SI Affair
        </di>
        <!-- DETAIL -->
        {{-- ================= REGULER ================= --}}
        <h4>Detail</h4>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>No SJ</th>
                    <th>Box</th>
                    <th>Koli</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $regularItems = $loading->details->filter(fn($d) => $d->boardingList?->barang?->type === 'REGULER');

                    $totalBox = 0;
                    $totalKoli = 0;
                @endphp

                @foreach ($regularItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">
                            {{ $item->boardingList?->barang?->sjcode ?? '-' }}
                        </td>
                        <td>{{ $item->box }}</td>
                        <td>{{ $item->koli }}</td>
                    </tr>

                    @php
                        $totalBox += $item->box ?? 0;
                        $totalKoli += $item->koli ?? 0;
                    @endphp
                @endforeach

                <tr>
                    <td colspan="2" class="text-left"><b>Total Detail</b></td>
                    <td><b>{{ $totalBox }}</b></td>
                    <td><b>{{ $totalKoli }}</b></td>
                </tr>
            </tbody>
        </table>



        <!-- SIGNATURE -->
        <div class="footer">
            <div class="signature">
                Menyetujui<br>Good Delivery
                <div>{{ $loading->creator->name }}</div>
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

</body>

</html>
