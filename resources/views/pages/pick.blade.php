{{-- @extends('layouts.app')
@section('title', 'Picking')
@section('loader')
    @include('partials.loader')
@endsection
@section('content') --}}
<div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>End Picker Operation</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form class="row g-3" id="endPickForm">
                <div class="col-lg-12">
                    <x-form.input-btn placeholder="Masukkan Code SJ" btnTxt="Scan" btnId="pickBtn" name="codesj"
                        type="basic" invalid="Harap Masukkan Code SJ" label="Surat Jalan" id="sjpickend"
                        disabled="{{ false }}" value="tag1, tag2 autofocus" />
                </div>
                <button type="submit" class="btn btn-primary mb-2 me-4 ">End Pick</button>
            </form>
        </div>
    </div>
</div>
<div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>DATA PICKER</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <table id="pickTable" class="table style-3 dt-table-hover">
                <thead class="table-header">
                    <tr>
                        <th class="text-center col-no">No</th>
                        <th class="text-center col-no">Picker</th>
                        <th class="text-center col-no">SJ CODE</th>
                        <th class="text-center col-no">Status</th>
                        <th class="text-center col-no">Durasi</th>
                        <th class="text-center col-no">Tanggal</th>
                        <th class="text-center col-action">
                            <i class="fa fa-cog"></i>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

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

        moment.locale('id')
        $pickTable = $('#pickTable').DataTable({
            processing: true,
            ajax: {
                url: '/pick/items',
                type: 'GET',

                dataSrc: function(json) {
                    console.log(json.data)
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
                    className: 'text-center',
                    data: 'picker.name',
                },
                {
                    className: 'text-center',
                    data: 'barang.sjcode'
                },
                {
                    className: 'text-center',
                    data: 'status',
                    render: function(data, type, row) {
                        if (type !== 'display') return data;
                        return data.toUpperCase();
                    }
                },
                {
                    className: 'text-center',

                    data: 'started_at',
                    render: function(data, type, row) {
                        if (type !== 'display') return data;

                        // kalau sudah selesai → hitung durasi
                        if (row.finished_at) {
                            let start = moment.utc(data);
                            let end = moment.utc(row.finished_at);

                            let duration = moment.duration(end.diff(start));

                            let hours = Math.floor(duration.asHours());
                            let minutes = duration.minutes();

                            return `${hours} jam ${minutes} menit`;
                        }

                        // kalau belum selesai → tampilkan waktu relatif
                        return moment.utc(data).local().fromNow();
                    }
                },
                {
                    className: 'text-center',

                    data: 'created_at',
                    render: function(data, type, row) {
                        if (type !== 'display') return data;
                        return moment.utc(data).local().format('LLL');
                    }
                },
                {
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (row.status === 'finished') {
                            return '-';
                        }

                        return `<button type="button" 
                                class="btn btn-danger btn-sm endPick-btn"
                                data-id="${row.id}">
                                End Pick
                            </button>`;
                    }
                },
            ]
        })

        $(document).on('click', '.endPick-btn', function() {
            let pickId = $(this).data('id');

            $.ajax({
                url: `/pick/${pickId}/end`,
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Pick operation ended successfully'
                    });
                    $pickTable.ajax.reload(); // Refresh data table
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message ?? 'Login gagal';
                    let errors = xhr.responseJSON.data;
                    console.log(errors)
                    Toast.fire({
                        icon: 'error',
                        title: message
                    });
                }
            });
        });

        $('#endPickForm').on('submit', function(e) {
            e.preventDefault();

            let data = $(this).serializeArray()
            console.log(data)
            $.ajax({
                url: '/pick/end',
                method: 'PATCH',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.error
                    });
                    $('#endPickForm')[0].reset();
                    tagify.removeAllTags();
                    $pickTable.ajax.reload();
                },
                error: function(xhr) {
                    let errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    console.log(xhr.responseJSON.errors)
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMsg
                    });
                }


            });
        })

    });
</script>
{{-- @endsection --}}
