<div class="modal fade" id="scanBarcode" tabindex="-1" aria-labelledby="Scanner" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode Surat Jalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                <div id="reader" style="width:100%"></div>
            </div>
        </div>
    </div>
</div>


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
