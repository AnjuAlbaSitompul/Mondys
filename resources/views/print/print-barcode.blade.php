<!DOCTYPE html>
<html>

<head>
    <title>Print Barcode</title>

    <style>
        @page {
            size: 50mm 40mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 1.5mm;
            width: 50mm;
            height: 40mm;
            font-family: Arial, sans-serif;
        }

        .label {
            width: 100%;
            height: 100%;
            margin-top: 1mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .center {
            align-self: center;
        }

        .barcode-full svg {
            width: 100%;
            height: 15mm;
        }

        .barcode-sj svg {
            width: 100%;
            height: 15mm;
        }

        .big-text {
            font-size: 11px;
            font-weight: bold;
            margin-top: 1mm;
        }

        .footer-container {
            flex-direction: row;
            justify-content: space-between;
        }

        .footer {
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">

    @foreach ($barcodes as $item)
        @php
            $codeOutlet = substr($item['full'], 0, 4);
            $today = date('d'); // <-- ini yang diubah
        @endphp

        <div class="label">

            {{-- BARCODE FULL --}}
            <div class="barcode-full center">
                {!! DNS1D::getBarcodeSVG($item['full'], 'C128', 0.6, 50) !!}
            </div>

            {{-- BARCODE SJ --}}
            <div class="barcode-sj center">
                {!! DNS1D::getBarcodeSVG($item['sj'], 'C128', 0.6, 40) !!}
            </div>

            {{-- OUTLET --}}
            <div class="footer-container">
                <div class="big-text">
                    {{ $codeOutlet }}
                </div>

                {{-- TANGGAL (DD) --}}
                <div class="footer">
                    {{ $today }}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>
    @endforeach

</body>

</html>
