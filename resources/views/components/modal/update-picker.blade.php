@props(['isUpdate' => false])

<div class="modal modal-lg fade" id="updatePicker" tabindex="-1" aria-labelledby="updatePicker" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Picker Untuk Pengambilan Barang</h5>
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
                <table id="updatePickerTable" class="table style-3 dt-table-hover">
                    <thead class="table-header">
                        <tr>
                            <th class="text-center col-no">No</th>
                            <th>Nama</th>
                            <th class="text-center">Status</th>
                            <th class="text-center col-action">
                                <i class="fa fa-cog"></i>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // init DataTable sekali
        let table = $('#updatePickerTable').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: '/users/picker',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{
                    data: null,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'Nama'
                },
                {
                    data: 'picking_today',
                    className: 'text-center',
                    render: function(data) {
                        let status = data > 0 ? 'Picking' : 'Stay';
                        let badge = data > 0 ? 'badge-warning' : 'badge-primary';

                        return `<span class="badge ${badge} mb-2 me-4">${status}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<button type="button" 
                        class="btn btn-primary btn-sm update-pickerbtn"
                        data-id="${row.id}" 
                        data-name="${row.name}">
                        Pilih
                    </button>`;
                    }
                }
            ]
        });

        // reload saat modal dibuka
        $('#updatePicker').on('shown.bs.modal', function() {
            table.ajax.reload(null, false);
        });


    });
</script>
