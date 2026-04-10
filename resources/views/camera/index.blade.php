@extends('layouts.app')
@section('title', 'Camera')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <div id="cameraContainer" style="position:fixed; top:0; left:0; width:100%; height:100%; background:black;">
        <video id="video" autoplay playsinline style="width:100%; height:100%; object-fit:cover;"></video>

        <button id="captureBtn"
            style="position:absolute; bottom:30px; left:50%; transform:translateX(-50%);
        padding:20px; border-radius:50%; background:white; border:none;">
            <i class="fa fa-camera"></i>
        </button>
    </div>

    <canvas id="canvas" style="display:none;"></canvas>

    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            let video = document.getElementById('video');
            let canvas = document.getElementById('canvas');

            // 🎥 Start Camera
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                } // back camera
            }).then(stream => {
                video.srcObject = stream;
            }).catch(err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Bisa Di Akses'
                })
            });

            // 📸 Capture
            $('#captureBtn').click(function() {
                let btn = $(this);
                btn.prop('disabled', true);
                btn.css('opacity', '0.5');
                let context = canvas.getContext('2d');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // convert ke blob
                canvas.toBlob(function(blob) {
                    let formData = new FormData();
                    formData.append('photo', blob, 'clockin.jpg');

                    // 🚀 AJAX kirim ke Laravel
                    $.ajax({
                        url: '/deliver/clock-in',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Kamu Sudah Clock In'
                            })
                        },
                        error: function(err) {
                            btn.prop('disabled', false);
                            btn.css('opacity', '1');
                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan'
                            })
                        }
                    });
                }, 'image/jpeg', 0.8);
            });
        });
    </script>
@endsection
