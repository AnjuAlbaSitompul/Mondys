<div class="modal fade" id="scanBarcodeDriver" tabindex="-1" aria-labelledby="Scanner" aria-hidden="true">
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
    let scanned = false;

    $('#scanBarcodeDriver').on('shown.bs.modal', function() {

        scanned = false;

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            function(decodedText) {

                if (scanned) return;
                scanned = true;

                // stop camera
                html5QrCode.stop().then(() => {

                    // 🔥 AJAX CHECK
                    $.ajax({
                        url: `/loading/get/${decodedText}`,
                        type: 'GET',
                        success: function(res) {

                            // ✅ kalau valid → Swal confirm
                            Swal.fire({
                                title: 'Valid Loading',
                                text: 'Lanjutkan ke delivering?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((result) => {

                                if (result.isConfirmed) {

                                    // 🔥 CREATE DELIVERING
                                    $.ajax({
                                        url: '/deliver/create',
                                        type: 'POST',
                                        data: {
                                            id: decodedText,
                                            _token: $(
                                                'meta[name="csrf-token"]'
                                            ).attr('content')
                                        },
                                        success: function(res) {
                                            $('#clockInBtn').attr(
                                                'data-id', res
                                                .data.id
                                            ).show();
                                            $('#scanBtn').hide();
                                            $('#scanBarcodeDriver')
                                                .modal('hide');
                                            Swal.fire('Success',
                                                'Delivering dibuat',
                                                'success');
                                        },
                                        error: function() {
                                            $('#clockInBtn').hide();
                                            $('#scanBtn').show();
                                            $('#scanBarcodeDriver')
                                                .modal('hide')
                                            Swal.fire('Error',
                                                'Gagal membuat delivering',
                                                'error');
                                        }
                                    });

                                }

                            });

                        },
                        error: function() {

                            // ❌ kalau tidak valid
                            Swal.fire({
                                title: 'Error',
                                text: 'Loading tidak valid / tidak ditemukan',
                                icon: 'error'
                            });

                            scanned = false; // allow scan ulang
                        }
                    });

                });

            }
        );

    });

    // stop camera kalau modal ditutup
    $('#scanBarcodeDriver').on('hidden.bs.modal', function() {
        if (html5QrCode) {
            html5QrCode.stop().catch(err => {});
        }
    });
</script>
