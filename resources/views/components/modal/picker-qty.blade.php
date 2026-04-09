<div class="modal modal-lg fade" id="pickerQty" tabindex="-1" aria-labelledby="pickerQtyLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Split items boarding</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="splitForm">

                <div class="modal-body">
                    <div class="muted-text mb-3">Masukkan Jumlah Koli</div>
                    <div class="col-lg-12">
                        <x-form.input placeholder="Masukkan Jumlah Koli" name="koli" type="number"
                            invalid="Harap Masukkan Jumlah Koli" label="Jumlah Koli" id="splitKoliCount" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="confirmQty">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
