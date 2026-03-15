@extends('layouts.app')
@section('title', 'Surat Jalan')
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
                            <h4>Pilih Picker Untuk Pengambilan Barang</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form class="row g-3" id="barang">
                        <div class="col-md-4">
                            <x-form.select :options="[
                                '' => 'Pilih Tipe Barang',
                                'sj' => 'Surat Jalan',
                                'titip' => 'Titip',
                            ]" name="type" label="Tipe Barang" id="type" />
                        </div>
                        <div class="col-lg-12 form">
                            <x-form.select label="Dari Outlet" name="outletId" :options="[
                                null => 'Pilih Outlet',
                                'HS01' => 'Haritsa Aceh',
                            ]" />
                        </div>
                        <div class="col-lg-12 sjcode">
                            <x-form.input-btn placeholder="Masukkan Code SJ" btnTxt="Scan" btnId="suratjalanBtn"
                                name="codesj" type="text" invalid="Harap Masukkan Code SJ" label="Surat Jalan"
                                id="sj" disabled="{{ false }}" toggle="modal" target="#scannerModal" />
                        </div>
                        <div class="col-lg-12">
                            <x-form.input-btn placeholder="Pilih Picker" btnTxt="Choose" btnId="choosePicker" name="userId"
                                type="text" invalid="Harap Masukkan Picker" label="Pilih Picker" id="userId"
                                disabled="{{ true }}" target="" toggle="" />
                        </div>
                        <div class="col-lg-12">
                            <textarea name="desc" class="form-control" placeholder="Masukkan Deskripsi Barang"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 me-4">Tambahkan Barang</button>
                    </form>
                    <x-modal.scan-modal inputId="barcode" />
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.sjcode').hide();
            $('.form').hide();
            $('#type').change(function() {

                let value = $(this).val();

                if (value === 'sj') {
                    $('.sjcode').show();
                    $('.form').hide();
                } else if (value === 'titip') {
                    $('.sjcode').hide();
                    $('.form').show();
                } else {
                    $('.sjcode').hide();
                    $('.form').hide();
                }

            });

            $('#barang').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serializeArray();

                console.log(formData);
            })
        })
    </script>
@endsection
