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
    #submitRating {
        transition: all 0.2s ease;
    }

    #submitRating:hover {
        background: #000;
        transform: translateY(-1px);
    }
</style>
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4">

            <div class="text-center mb-3">
                <h5 class="fw-semibold mb-1">Rate Your Experience</h5>
                <small class="text-muted">Kasih Rating Buat Driver</small>
            </div>

            <!-- ⭐ Stars -->
            <div class="d-flex justify-content-center gap-2 mb-4" id="starRating">
                <i class="fa fa-star star" data-value="1"></i>
                <i class="fa fa-star star" data-value="2"></i>
                <i class="fa fa-star star" data-value="3"></i>
                <i class="fa fa-star star" data-value="4"></i>
                <i class="fa fa-star star" data-value="5"></i>
            </div>

            <input type="hidden" id="ratingValue">

            <!-- Comment -->
            <textarea class="form-control border-0 bg-light rounded-3 px-3 py-2 mb-3" id="comment"
                placeholder="Tulis sedikit feedback..."></textarea>

            <!-- Action -->
            <button class="btn btn-dark w-100 rounded-3 py-2" id="submitRating">
                Submit
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
        let rating = 0;

        $('.star').click(function() {
            rating = $(this).data('value');
            $('#ratingValue').val(rating);

            $('.star').removeClass('active');

            $('.star').each(function() {
                if ($(this).data('value') <= rating) {
                    $(this).addClass('active');
                }
            });
        });
        $('#submitRating').click(function() {
            let id = $(this).data('id');
            if (!rating) {
                return alert('Pilih rating dulu ya');
            }
            $.ajax({
                url: `/rating/driver/${id}`,
                type: 'POST',
                data: {
                    rating: rating,
                    comment: $('#comment').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(this).prop('disabled', true).text('Loading...');
                },
                success: function(res) {
                    $('#ratingModal').modal('hide');

                    // optional notif
                    Toast.fire({
                        icon: 'success',
                        title: "Terimakasih Atas Ratingnya"
                    });
                    
                },
                error: function(err) {
                    const message = err.responseJSON?.message || "Terjadi Kesalahan"
                    Toast.fire({
                        icon: 'error',
                        title: message
                    })
                }
            })
        });

    });
</script>
