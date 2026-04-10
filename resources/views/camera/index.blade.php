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
            📸
        </button>
    </div>

    <canvas id="canvas" style="display:none;"></canvas>

    <script>
        $(document).ready(function() {
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
                alert('Camera tidak bisa diakses 😢');
                console.log(err);
            });

            // 📸 Capture
            $('#captureBtn').click(function() {
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
                            alert('Clock-in berhasil 😎');
                        },
                        error: function(err) {
                            alert('Gagal kirim 😢');
                            console.log(err);
                        }
                    });
                }, 'image/jpeg', 0.8);
            });
        });
    </script>
@endsection
