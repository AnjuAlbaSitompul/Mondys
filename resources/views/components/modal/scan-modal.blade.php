@props(['inputId'])
{{-- <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="Scanner" aria-hidden="true">
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

<script src="https://unpkg.com/html5-qrcode"></script> --}}
<style>
    .camera-view {
        position: relative;
        width: 100%;
        height: 100dvh;
        max-height: 100vh;
        background: black;
        overflow: hidden;
    }

    /* 🔥 jangan zoom (biar barcode gak blur) */
    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
        /* 🔥 penting */
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

    /* 🔥 horizontal box (penting untuk barcode) */
    .scan-box {
        width: min(80vw, 320px);
        height: min(80vw, 320px);
        /* 🔥 pendek, bukan kotak */
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

<div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-black border-0">

            <div class="camera-header">
                <span>Scan Barcode</span>
                <button class="btn text-white fs-4" data-bs-dismiss="modal">✕</button>
            </div>

            <div class="modal-body p-0 position-relative">
                <div id="reader" class="camera-view"></div>

                <div class="scan-overlay">
                    <div class="scan-box"></div>
                </div>
            </div>

            <div class="camera-footer">
                Arahkan barcode ke dalam kotak
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
        if (!devices.length) return null;
        console.log(devices)
        const back = devices.find(d =>
            d.label.toLowerCase().includes('back') ||
            d.label.toLowerCase().includes('rear')
        );

        return back ? back.id : devices[0].id;
    }

    async function startScanner() {
        if (isScanning) return;

        html5QrCode = new Html5Qrcode("reader");

        try {
            isScanning = true;

            const config = {
                fps: 10,
                qrbox: {
                    width: 300,
                    height: 300
                }, // 🔥 horizontal (penting!)
                aspectRatio: 1.777, // 🔥 biar landscape, cocok barcode
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.CODE_128
                ],
                videoConstraints: {
                    facingMode: "environment",
                    width: {
                        ideal: 1920
                    },
                    height: {
                        ideal: 1080
                    }
                }
            };

            let cameraConfig;

            if (isMobile()) {
                cameraConfig = {
                    facingMode: {
                        exact: "environment"
                    }
                };
            } else {
                const id = await getBackCamera();
                cameraConfig = id ? {
                    deviceId: {
                        exact: id
                    }
                } : {
                    facingMode: "environment"
                };
            }

            await html5QrCode.start(
                cameraConfig,
                config,
                (decodedText) => {
                    let input = document.querySelector('#{{ $inputId }}');

                    let tagify = new Tagify(input);
                    tagify.addTags(decodedText);

                    stopScanner().then(() => {
                        $('#scannerModal').modal('hide');
                        // $('#pickerQty').modal('show');
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

            // 🔥 optional zoom (kalau device support)
            setTimeout(() => {
                try {
                    const track = html5QrCode.getRunningTrack();
                    const cap = track.getCapabilities();

                    if (cap.zoom) {
                        track.applyConstraints({
                            advanced: [{
                                zoom: cap.zoom.max / 2
                            }]
                        });
                    }
                } catch (e) {}
            }, 1000);

        } catch (err) {
            console.error(err);
            isScanning = false;
        }
    }

    async function stopScanner() {
        if (!html5QrCode || !isScanning) return;

        try {
            await html5QrCode.stop();
            await html5QrCode.clear();
        } catch (e) {}

        html5QrCode = null;
        isScanning = false;
    }

    $('#openScan').on('click', function() {
        $('#scannerModal').modal('show');
    });

    $('#scannerModal').on('shown.bs.modal', function() {
        setTimeout(startScanner, 300);
    });

    $('#scannerModal').on('hidden.bs.modal', function() {
        stopScanner();
    });
</script>


{{-- <script>
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

                let input = document.querySelector('#{{ $inputId }}');

                let tagify = new Tagify(input);
                tagify.addTags(decodedText);

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
</script> --}}
