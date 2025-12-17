@extends('layouts.frontend')

@section('title', 'Portal Akses - Universitas Muhammadiyah Maluku')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            {{-- <img src="{{ asset('img/logo.png') }}" alt="Logo" height="80" class="mb-3"> --}}
            <h1 class="fw-bold text-dark">Portal Akses Terpadu</h1>
            <p class="lead text-muted">Universitas Muhammadiyah Maluku</p>
        </div>

        <div class="row justify-content-center">
            <!-- Calon Mahasiswa -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-lift text-center">
                    <div class="card-body p-4">
                        <div class="icon-box bg-warning text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-plus fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold">Calon Mahasiswa</h5>
                        <p class="card-text text-muted small">Informasi pendaftaran dan seleksi masuk mahasiswa baru.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('pmb.index') }}" class="btn btn-outline-warning">Info Pendaftaran</a>
                            <a href="{{ route('login.camaru') }}" class="btn btn-warning text-white">Login Camaru</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mahasiswa -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-lift text-center">
                    <div class="card-body p-4">
                        <div class="icon-box bg-primary text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-graduate fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold">Mahasiswa</h5>
                        <p class="card-text text-muted small">Akses Kartu Rencana Studi (KRS), KHS, dan layanan akademik.</p>
                        <div class="d-grid">
                            <a href="{{ route('login.mahasiswa') }}" class="btn btn-primary">Login Mahasiswa</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dosen & Tendik -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-lift text-center">
                    <div class="card-body p-4">
                        <div class="icon-box bg-success text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold">Dosen & Tendik</h5>
                        <p class="card-text text-muted small">Portal layanan untuk Dosen dan Tenaga Kependidikan.</p>
                        <div class="d-grid">
                            <a href="{{ route('login.staff') }}" class="btn btn-success">Login Staff</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Administrator -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-lift text-center">
                    <div class="card-body p-4">
                        <div class="icon-box bg-dark text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-shield fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold">Administrator</h5>
                        <p class="card-text text-muted small">Panel pengelolaan sistem informasi akademik.</p>
                        <div class="d-grid">
                            <a href="{{ route('login.admin') }}" class="btn btn-dark">Login Admin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 text-muted small">
            &copy; {{ date('Y') }} Universitas Muhammadiyah Maluku
        </div>
    </div>
</div>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection
