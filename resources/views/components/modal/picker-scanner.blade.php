<style>
    .camera-view {
        position: relative;
        width: 100%;
        height: 100dvh;
        max-height: 100vh;
        background: black;
        overflow: hidden;
    }

    /* paksa video fullscreen */
    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    #reader {
        border: none !important;
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

    /* scan box */
    .scan-box {
        width: min(70vw, 260px);
        height: min(70vw, 260px);
        border: 3px solid #00ff99;
        border-radius: 12px;
        position: relative;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.6);
    }

    /* garis scan */
    .scan-box::after {
        content: "";
        position: absolute;
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

    /* header */
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
        color: white;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), transparent);
    }

    /* footer */
    .camera-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 20;
        padding: 16px;
        text-align: center;
        color: white;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
    }
</style>

<!-- modal -->
<div class="modal fade" id="scanBarcode" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-black border-0">

            <!-- header -->
            <div class="camera-header">
                <span>Scan Barcode</span>
                <button class="btn text-white fs-4" data-bs-dismiss="modal">✕</button>
            </div>

            <!-- camera -->
            <div class="modal-body p-0 position-relative">
                <div id="reader" class="camera-view"></div>

                <div class="scan-overlay">
                    <div class="scan-box"></div>
                </div>
            </div>

            <!-- footer -->
            <div class="camera-footer">
                Arahkan kamera ke barcode
            </div>

        </div>
    </div>
</div>
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode = null;
    let isScanning = false;

    function isMobile() {
        return /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    }

    async function getBackCamera() {
        const devices = await Html5Qrcode.getCameras();

        if (!devices || !devices.length) return null;

        // 🔥 cari kamera belakang berdasarkan label
        const backCamera = devices.find(device =>
            device.label.toLowerCase().includes('back') ||
            device.label.toLowerCase().includes('rear') ||
            device.label.toLowerCase().includes('environment')
        );

        return backCamera ? backCamera.id : devices[0].id;
    }

    async function startScanner() {
        if (isScanning) return;

        html5QrCode = new Html5Qrcode("reader");

        try {
            isScanning = true;

            const config = {
                fps: 10,
                qrbox: (w, h) => {
                    let size = Math.min(w, h) * 0.6;
                    return {
                        width: size,
                        height: size
                    };
                },
                aspectRatio: 1
            };

            let cameraConfig;

            if (isMobile()) {
                // 📱 MOBILE → pakai belakang
                cameraConfig = {
                    facingMode: {
                        exact: "environment"
                    }
                };
            } else {
                // 💻 DESKTOP → pilih device
                const cameraId = await getBackCamera();

                cameraConfig = cameraId ? {
                    deviceId: {
                        exact: cameraId
                    }
                } : {
                    facingMode: "environment"
                };
            }

            await html5QrCode.start(
                cameraConfig,
                config,
                (decodedText) => {

                    alert("Scanned: " + decodedText);

                    stopScanner().then(() => {
                        $('#scanBarcode').modal('hide');
                    });

                }
            );

            // 🔥 iOS fix
            setTimeout(() => {
                const video = document.querySelector('#reader video');
                if (video) {
                    video.setAttribute("playsinline", true);
                    video.setAttribute("muted", true);
                }
            }, 200);

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

        html5QrCode = null;
        isScanning = false;
    }

    // buka modal
    $('#openScan').on('click', function() {
        $('#scanBarcode').modal('show');
    });

    // start camera
    $('#scanBarcode').on('shown.bs.modal', function() {
        setTimeout(() => {
            startScanner();
        }, 300);
    });

    // stop camera
    $('#scanBarcode').on('hidden.bs.modal', function() {
        stopScanner();
    });
</script>
