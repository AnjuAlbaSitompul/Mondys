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
                    <form class="row g-3">
                        <div class="col-md-4">
                            <x-form.select :options="[
                                '' => 'Pilih Tipe Barang',
                                'sj' => 'Surat Jalan',
                                'titip' => 'Titip',
                            ]" name="type" label="Tipe Barang" />
                        </div>
                        <div class="col-lg-12">
                            <x-form.select label="Tujuan Outlet" name="outletId" :options="[
                                null => 'Pilih Tujuan',
                                'HS01' => 'Haritsa Aceh',
                            ]" />
                        </div>
                        <div class="col-lg-12">
                            <x-form.input-btn placeholder="Masukkan Code SJ" btnTxt="Scan" btnId="suratjalanBtn"
                                name="codesj" type="text" invalid="Harap Masukkan Code SJ" label="Surat Jalan"
                                id="sj" disabled="{{ false }}" />
                        </div>
                        <div class="col-lg-12">
                            <x-form.input-btn placeholder="Pilih Picker" btnTxt="Choose" btnId="choosePicker" name="userId"
                                type="text" invalid="Harap Masukkan Picker" label="Pilih Picker" id="userId"
                                disabled="{{ true }}" />
                        </div>
                    </form>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#scannerModal">
                        Scan Barcode
                    </button>
                    <x-modal.scan-modal inputId="barcode" />
                </div>
            </div>
        </div>
    </div>
@endsection
