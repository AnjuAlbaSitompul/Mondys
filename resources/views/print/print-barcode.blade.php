<!DOCTYPE html>
<html>

<head>
    <title>Print Barcode</title>

    <style>
        @page {
            size: auto;
            margin: 2mm;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
        }

        .label {
            width: 50mm;
            height: 40mm;
            border: 1px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 2mm;
            page-break-inside: avoid;
        }

        .barcode {
            margin-bottom: 4px;
        }

        .text {
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container">
        @foreach ($barcodes as $code)
            <div class="label">

                <div class="barcode">
                    {!! DNS1D::getBarcodeHTML($code, 'C128', 1.5, 40) !!}
                </div>

                <div class="text">
                    {{ $code }}
                </div>

            </div>
        @endforeach
    </div>

</body>

</html>
