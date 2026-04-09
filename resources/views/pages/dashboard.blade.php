@extends('layouts.app')
@section('title', 'Dashboard')
@section('loader')
    @include('partials.loader')
@endsection
@section('content')

    @php
        $role = strtolower($user->role);
    @endphp

    @includeIf("dashboard.$role.index")
@endsection
