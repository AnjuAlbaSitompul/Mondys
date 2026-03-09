<div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                <div id="reader" style="width:100%"></div>

                <input type="text" id="{{ $inputId }}" class="form-control mt-3" readonly>

            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode;

    $('#scannerModal').on('shown.bs.modal', function() {

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            function(decodedText) {

                $('#{{ $inputId }}').val(decodedText);

                html5QrCode.stop().then(() => {
                    $('#scannerModal').modal('hide');
                });

            }
        );

    });

    $('#scannerModal').on('hidden.bs.modal', function() {

        if (html5QrCode) {
            html5QrCode.stop().catch(err => {});
        }

    });
</script>
