@php
    $total = count($barcodes);
    $availableHeight = 38;
    $perItemHeight = max(8, min(12, $availableHeight / max($total, 1)));
@endphp

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
            padding: 1mm;
            width: 50mm;
            height: 40mm;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .item {
            align-items: 'center';
            margin-bottom: 1mm;
        }

        .text {
            font-size: 6px;
            line-height: 1;
        }

        svg {
            width: 100%;
            max-width: 100%;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container">
        @foreach ($barcodes as $code)
            <div class="item">
                {!! DNS1D::getBarcodeSVG($code, 'C128', 0.6, $perItemHeight * 3) !!}
            </div>
        @endforeach
    </div>

</body>

</html>
