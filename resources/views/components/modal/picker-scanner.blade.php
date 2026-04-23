<style>
    .camera-view {
        width: 100%;
        height: 100dvh;
        max-height: 100vh;
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

    /* 🔥 SAMAKAN DENGAN qrbox */
    .scan-box {
        /* width: min(70vw, 250px);
        height: min(70vw, 250px); */
        /* border: 3px solid #00ff99;
        border-radius: 12px;
        position: relative;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.6); */
    }

    /* scan line */
    .scan-box::after {
        content: "";
        position: absolute;
        left: 0;
        width: 100%;
        height: 2px;
        background: #00ff99;
        animation: scan 2s linear infinite;
    }

    @keyframes scan {
        0% {
            top: 0;
        }

        100% {
            top: 100%;
        }
    }

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
</style>
<div class="modal fade" id="scanBarcode" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-black border-0">

            <div class="camera-header text-white">
                <div class="d-flex align-items-center gap-2">
                    <span>Scan Barcode</span>
                    <select id="cameraSelect" class="form-select form-select-sm d-none d-md-block"></select>
                </div>

                <button type="button" class="btn text-white fs-4" data-bs-dismiss="modal">✕</button>
            </div>

            <div class="modal-body p-0 position-relative">
                <div id="reader" class="camera-view"></div>

                <div class="scan-overlay">
                    <div class="scan-box"></div>
                </div>
            </div>

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
    let currentCameraId = null;

    // load camera list (desktop support)
    async function loadCameras() {
        try {
            const devices = await Html5Qrcode.getCameras();
            const select = $('#cameraSelect');
            select.empty();

            devices.forEach(device => {
                select.append(`<option value="${device.id}">${device.label || 'Camera'}</option>`);
            });

            if (devices.length) {
                currentCameraId = devices[0].id;
            }

        } catch (err) {
            console.error("Camera load error:", err);
        }
    }

    async function startScanner() {
        if (isScanning) return;

        html5QrCode = new Html5Qrcode("reader");

        try {
            isScanning = true;

            let config = {
                fps: 10,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    let size = Math.min(viewfinderWidth, viewfinderHeight) * 0.6;
                    return {
                        width: size,
                        height: size
                    };
                }
            };

            let cameraConfig;

            // mobile fallback
            if (!currentCameraId) {
                cameraConfig = {
                    facingMode: "environment"
                };
            } else {
                cameraConfig = {
                    deviceId: {
                        exact: currentCameraId
                    }
                };
            }

            await html5QrCode.start(
                cameraConfig,
                config,
                (decodedText) => {
                    alert(`Scanned: ${decodedText}`);
                    $('#confirmQty').attr('data-id', decodedText);

                    stopScanner().then(() => {
                        $('#scanBarcode').modal('hide');
                        $('#pickerQty').modal('show');
                    });

                }
            );

        } catch (err) {
            console.error("Camera start error:", err);
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

    // switch camera (desktop)
    $('#cameraSelect').on('change', function() {
        currentCameraId = $(this).val();

        if (isScanning) {
            stopScanner().then(startScanner);
        }
    });

    // open modal
    $('#openScan').on('click', function() {
        $('#scanBarcode').modal('show');
    });

    // start
    $('#scanBarcode').on('shown.bs.modal', function() {
        loadCameras();

        setTimeout(() => {
            startScanner();
        }, 300);
    });

    // stop
    $('#scanBarcode').on('hidden.bs.modal', function() {
        stopScanner();
    });
</script>
