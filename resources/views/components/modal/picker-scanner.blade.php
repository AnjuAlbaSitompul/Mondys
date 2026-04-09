<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode;

    $('#scanBarcode').on('shown.bs.modal', function() {

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            function(decodedText) {

                // set barcode ke tombol / input
                $('#confirmQty').attr('data-id', decodedText);

                // stop camera dulu
                html5QrCode.stop().then(() => {

                    // tutup modal scan
                    $('#scanBarcode').modal('hide');

                    // buka modal qty
                    $('#pickerQty').modal('show');
                });

            }
        );

    });

    // stop camera kalau modal ditutup manual
    $('#scanBarcode').on('hidden.bs.modal', function() {
        if (html5QrCode) {
            html5QrCode.stop().catch(err => {});
        }
    });
</script>
