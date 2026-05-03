<style>
    .modal-content {
        background: #fff;
    }

    /* ⭐ Stars */
    .star {
        font-size: 28px;
        color: #e4e4e4;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star.active {
        color: #111;
    }

    .star:hover {
        transform: scale(1.15);
    }

    /* textarea clean look */
    textarea:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    /* button hover */
    #submitClaim {
        transition: all 0.2s ease;
    }

    #submitClaim:hover {
        background: #000;
        transform: translateY(-1px);
    }
</style>
<div class="modal fade" id="claimModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4">

            <div class="text-center mb-3">
                <h5 class="fw-semibold mb-1">Claim Barang Anda</h5>
                <small class="text-muted textClaim">Claim Barang dengan sj</small>
            </div>

            <input type="hidden" id="ratingValue">

            {{-- input picture --}}
            <div class="mb-3">
                <input class="form-control border-0 bg-light rounded-3 px-3 py-2 mb-3" type="file" id="claimImage"
                    accept="image/*" placeholder="Bukti Gambar" name="claimImage">
            </div>
            <!-- claim -->
            <textarea class="form-control border-0 bg-light rounded-3 px-3 py-2 mb-3" id="claim"
                placeholder="Masukkan Alasan Claim.." name="claim"></textarea>

            <!-- Action -->
            <button class="btn btn-dark w-100 rounded-3 py-2" id="submitClaim">
                Claim
            </button>

        </div>
    </div>
</div>

<script>
    $(function() {
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
        })
        $('#submitClaim').click(function() {
            let barangId = $(this).data('id');
            let desc = $('#claim').val();
            let image = $('#claimImage')[0].files[0];

            let formData = new FormData();
            formData.append('barang_id', barangId);
            formData.append('desc', desc);
            formData.append('image', image);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '/claim',
                type: 'POST',
                data: formData,
                processData: false, // penting
                contentType: false, // penting
                beforeSend: function() {
                    $('#submitClaim').prop('disabled', true).text('Loading...');
                },
                success: function(res) {
                    $('#claimModal').modal('hide');

                    Toast.fire({
                        icon: 'success',
                        title: "Claim Berhasil, Silahkan Tunggu Response"
                    });

                    resetClaimModal();
                },
                error: function(err) {
                    const message = err.responseJSON.message || 'Terjadi Kesalahan'
                    Toast.fire({
                        icon: 'error',
                        title: message
                    });
                },
                complete: function() {
                    $('#submitClaim').prop('disabled', false).text('Submit');
                }
            });
        });

        function resetClaimModal() {
            $('#submitClaim').removeAttr('data-id');
            $('#claimDesc').val('');
            $('.textClaim').text('');
        }

        $('#claimModal').on('hidden.bs.modal', function() {
            resetClaimModal();
        });
    });
</script>
