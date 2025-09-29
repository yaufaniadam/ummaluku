{{-- Kode ini untuk file: resources/views/dashboard.blade.php --}}

@extends('adminlte::page')

{{-- Judul Halaman --}}
@section('title', 'Dashboard')

{{-- Header Konten (Judul di dalam halaman) --}}
@section('content_header')
    <h1>Dashboard</h1>
@stop

{{-- Konten Utama Halaman --}}
@section('content')
    <div class="alert alert-success">
        <h4><i class="icon fas fa-check"></i> Selamat Datang!</h4>
        <p>Halo, <strong>{{ Auth::user()->name }}</strong>! Anda telah berhasil login ke dalam sistem.</p>
    </div>

    {{-- Baris untuk Info Box (Widgets) --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- Small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \App\Models\Application::where('status', 'lengkapi_data')->count() }}</h3>
                    <p>Calon Mahasiswa </p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('admin.pmb.pendaftaran.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        {{-- <div class="col-lg-3 col-6">
            <!-- Small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\StudyClass::count() }}</h3>
                    <p>Total Kelas Dibuka</p>
                </div>
                <div class="icon">
                    <i class="fas fa-school"></i>
                </div>
                <a href="{{ route('study-classes.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div> --}}
        {{-- <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Models\Invoice::where('status', '!=', 'Paid')->count() }}</h3>
                    <p>Tagihan Belum Lunas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('invoices.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div> --}}
        <!-- ./col -->
        {{-- <div class="col-lg-3 col-6">
            <!-- Small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\PaymentSubmission::where('status', 'pending')->count() }}</h3>
                    <p>Verifikasi Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <a href="{{ route('admin.payment_verifications.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div> --}}
        <!-- ./col -->
    </div>
@stop

{{-- (Opsional) Jika butuh CSS kustom --}}
@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

{{-- (Opsional) Jika butuh JavaScript kustom --}}
@section('js')
    <script> console.log('Dasbor berhasil dimuat!'); </script>
@stop