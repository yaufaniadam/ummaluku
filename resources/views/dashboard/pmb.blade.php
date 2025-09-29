@extends('adminlte::page')
@section('title', 'Dashboard PMB')
@section('content_header')
    <h1>Dashboard Penerimaan Mahasiswa Baru (PMB)</h1>
    @if($activeBatch)
        <h5 class="font-weight-light">Gelombang Aktif: {{ $activeBatch->name }}</h5>
    @else
        <h5 class="font-weight-light text-danger">Tidak ada gelombang pendaftaran yang aktif.</h5>
    @endif
@stop
@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <x-stat-box title="Total Pendaftar" value="{{ $totalPendaftar }}" icon="fas fa-users" color="info" url="{{ route('admin.pmb.pendaftaran.index') }}"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Menunggu Pembayaran" value="{{ $pembayaranPending }}" icon="fas fa-money-bill-wave" color="warning" url="{{ route('admin.pmb.payment.index') }}"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Menunggu Kelengkapan Berkas" value="{{ $berkasPending }}" icon="fas fa-file-upload" color="primary" url="#"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pendaftar per Program Studi</h3>
                </div>
                <div class="card-body">
                    {{-- Canvas untuk Chart.js --}}
                </div>
            </div>
        </div>
    </div>
@stop