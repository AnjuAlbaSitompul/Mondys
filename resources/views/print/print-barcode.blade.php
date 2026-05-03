<!DOCTYPE html>
<html>

<head>
    <title>Print Barcode</title>

    <style>
        @page {
            size: 50mm 40mm;
            margin: 1mm;
        }

        body {
            margin: 0;
            padding: 1.5mm;
            width: 50mm;
            height: 40mm;
            font-family: Arial, sans-serif;
        }

        .label {
            display: flex;
            flex-direction: column;
        }

        /* === TOP AREA === */
        .top {
            display: flex;
            flex-direction: column;
        }

        /* === LEFT: BARCODE VERTICAL === */
        .barcode-vertical {
            height: 10mm;
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            /* penting */
        }

        .barcode-vertical svg {
            scale: 1.3;
        }

        /* === RIGHT: QR BOX === */
        .qr-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 26mm;
            text-align: center;
        }

        .qr-box svg {
            width: 24mm;
            height: 24mm;
            display: block;
            margin: 0 auto;
        }

        /* === FOOTER === */
        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1mm;
        }

        .big-text {
            font-size: 11px;
            font-weight: bold;
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

            <div class="top">

                {{-- LEFT: BARCODE --}}
                <div class="barcode-vertical">
                    {!! DNS1D::getBarcodeSVG($item['sj'], 'C128', 0.6, 30) !!}
                </div>

                {{-- RIGHT: QR --}}
                <div class="qr-box">
                    {!! DNS2D::getBarcodeSVG($item['full'], 'QRCODE', 4, 4) !!}
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="footer-container">
                <div class="big-text">
                    {{ $codeOutlet }} - {{ $item['koli'] }}
                </div>

                <div class="footer">
                    {{ $today }}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>
    @endforeach

</body>

</html>
