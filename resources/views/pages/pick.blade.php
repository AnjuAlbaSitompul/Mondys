@extends('layouts.app')
@section('title', 'Barang Picked')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <div class="row layout-top-spacing">

        <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Daftar Barang Yand Di Ambil</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <table id="pickTable" class="table style-3 dt-table-hover">
                        <thead class="table-header">
                            <tr>
                                <th class="text-center col-no">No</th>
                                <th class="text-center col-no">Picker</th>
                                <th class="text-center col-no">Status</th>

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
                        data: 'picker.name',
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'started_at',
                        render: function(data, type, row) {
                            // type bisa 'display', 'sort', dll
                            let time = new Date(data)
                            let idTime = time.toLocaleTimeString('id-ID')
                            if (type === 'display') {
                                return moment(data).local().fromNow(); // "2 jam yang lalu"
                            }
                            return data; // biar sorting tetap berdasarkan timestamp asli
                        }
                    },
                    {
                        className: 'text-center',
                        defaultContent: '-' // untuk barang tanpa outlet
                    },
                ]
            })
        })
    </script>
@endsection
