@extends('layouts.app')
@section('title', 'Dashboard')
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

            let stream = null;

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                }
            }).then(s => {
                stream = s;
                video.srcObject = stream;
            }).catch(() => {
                Toast.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Bisa Di Akses'
                });
            });

            $('#captureBtn').click(function() {
                let btn = $(this);

                if (!video.videoWidth) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Kamera belum siap'
                    });
                    return;
                }

                btn.prop('disabled', true).css('opacity', '0.5');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');

                let context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(blob) {
                    let formData = new FormData();
                    formData.append('photo', blob, 'clockin.jpg');

                    $.ajax({
                        url: '/deliver/clock-in/{{ $delivering->id }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {

                            if (stream) {
                                stream.getTracks().forEach(track => track.stop());
                            }

                            Toast.fire({
                                icon: 'success',
                                title: 'Kamu Sudah Clock In'
                            });

                            setTimeout(() => {
                                window.location.href = '/driver/dashboard';
                            }, 1500);
                        },
                        error: function(err) {
                            console.log(err);

                            btn.prop('disabled', false).css('opacity', '1');
                            btn.html('<i class="fa fa-camera"></i>');

                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan'
                            });
                        }
                    });
                }, 'image/jpeg', 0.8);
            });
        });
    </script>
@endsection
