@extends('adminlte::page')
@section('title', 'Dashboard Utama')
@section('content_header')
    <h1>Dashboard Utama</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            {{-- Kotak Menu PMB --}}
            <x-adminlte-small-box title="PMB" text="Manajemen Pendaftaran" icon="fas fa-door-open text-white"
                url="{{ route('admin.pmb.dashboard') }}" theme="info"/>
        </div>
        <div class="col-lg-3 col-6">
            {{-- Kotak Menu Akademik --}}
            <x-adminlte-small-box title="Akademik" text="Manajemen Akademik" icon="fas fa-graduation-cap text-white"
                url="{{ route('admin.akademik.dashboard') }}" theme="success"/>
        </div>
        <div class="col-lg-3 col-6">
            {{-- Kotak Menu Keuangan --}}
            <x-adminlte-small-box title="Keuangan" text="Manajemen Keuangan" icon="fas fa-money-bill-wave text-white"
                url="{{ route('admin.keuangan.dashboard') }}" theme="warning"/>
        </div>
        <div class="col-lg-3 col-6">
            {{-- Kotak Menu SDM --}}
            <x-adminlte-small-box title="SDM" text="Manajemen Kepegawaian" icon="fas fa-users-cog text-white"
                url="{{ route('admin.sdm.dashboard') }}" theme="danger"/>
        </div>
    </div>

    {{-- Di sini bisa ditambahkan ringkasan data dari semua modul --}}
    <div class="alert alert-info">
        <h5 class="alert-heading"><i class="icon fas fa-info"></i> Ringkasan Sistem</h5>
        Selamat datang di Sistem Informasi Universitas. Dari sini Anda dapat memantau dan mengelola semua modul yang tersedia.
    </div>
@stop