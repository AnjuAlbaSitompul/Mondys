<style>
    /* 🔥 FIX iOS viewport issue */
    .camera-view {
        width: 100%;
        height: 100dvh;
        /* modern fix */
        max-height: 100vh;
        object-fit: cover;
        background: black;
    }

    /* overlay */
    .scan-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        pointer-events: none;
    }

    /* scan box responsive */
    .scan-box {
        width: min(70vw, 300px);
        height: min(70vw, 300px);
        border: 3px solid #00ff99;
        border-radius: 16px;
        position: relative;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.6);
    }

    /* animasi scan line */
    .scan-box::after {
        content: "";
        position: absolute;
        left: 0;
        width: 100%;
        height: 2px;
        background: #00ff99;
        animation: scan 2s linear infinite;
    }

    /* animation */
    @keyframes scan {
        0% {
            top: 0;
        }

        100% {
            top: 100%;
        }
    }

    /* header overlay */
    .camera-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 20;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;

        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), transparent);
    }

    /* footer (optional info / button) */
    .camera-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 20;
        padding: 16px;
        text-align: center;

        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
    }

    /* desktop adjustment */
    @media (min-width: 768px) {
        .scan-box {
            width: 320px;
            height: 320px;
        }
    }

    /* extra large screen */
    @media (min-width: 1200px) {
        .scan-box {
            width: 380px;
            height: 380px;
        }
    }
</style>
<div class="modal fade" id="scanBarcode" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-black border-0">

            <!-- HEADER -->
            <div class="camera-header text-white">
                <div class="d-flex align-items-center gap-2">
                    <span>Scan Barcode</span>
                    <select id="cameraSelect" class="form-select form-select-sm d-none d-md-block"></select>
                </div>

                <button type="button" class="btn text-white fs-4" data-bs-dismiss="modal">
                    ✕
                </button>
            </div>

            <!-- CAMERA -->
            <div class="modal-body p-0 position-relative">
                <div id="reader" class="camera-view"></div>

                <!-- overlay -->
                <div class="scan-overlay">
                    <div class="scan-box"></div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="camera-footer text-white small">
                Arahkan kamera ke barcode
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode = null;
    let isScanning = false;

    async function startScanner() {
        if (isScanning) return;

        html5QrCode = new Html5Qrcode("reader");

        try {
            isScanning = true;

            await html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1
                },
                (decodedText) => {

                    $('#confirmQty').attr('data-id', decodedText);

                    stopScanner().then(() => {
                        $('#scanBarcode').modal('hide');
                        $('#pickerQty').modal('show');
                    });

                }
            );

        } catch (err) {
            console.error("Camera error:", err);
            isScanning = false;
        }
    }

    async function stopScanner() {
        if (!html5QrCode || !isScanning) return;

        try {
            await html5QrCode.stop();
            await html5QrCode.clear();
        } catch (err) {}

        isScanning = false;
        html5QrCode = null;
    }

    $('#openScan').on('click', function() {
        $('#scanBarcode').modal('show');
    });

    // start setelah modal muncul
    $('#scanBarcode').on('shown.bs.modal', function() {
        setTimeout(() => {
            startScanner();
        }, 300);
    });

    // stop saat close
    $('#scanBarcode').on('hidden.bs.modal', function() {
        stopScanner();
    });
</script>
