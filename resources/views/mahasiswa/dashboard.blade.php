@extends('adminlte::page')

@section('title', 'Dashboard Mahasiswa')

@section('content_header')
    <h1>Dashboard Mahasiswa</h1>
@stop

@section('content')
    {{-- Baris Profil & Status KRS --}}
    <div class="row">
        <div class="col-md-8">
            {{-- Widget Profil --}}
            <x-adminlte-profile-widget name="{{ $student->user->name }}" desc="{{ $student->nim }} | Angkatan {{ $student->entry_year }}"
                theme="primary" img="https://picsum.photos/id/1/100">
                <x-adminlte-profile-col-item class="text-primary border-right" icon="fas fa-fw fa-book"
                    title="Program Studi" text="{{ $student->program->name_id }}" size=6/>
                <x-adminlte-profile-col-item class="text-danger" icon="fas fa-fw fa-user-tie"
                    title="Dosen PA" text="{{ $student->academicAdvisor->full_name_with_degree ?? 'Belum Diatur' }}" size=6/>
            </x-adminlte-profile-widget>
        </div>
        <div class="col-md-4">
            {{-- Widget Status KRS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status KRS Semester Ini</h3>
                </div>
                <div class="card-body">
                    <h5>{{ $activeSemester->name ?? 'Tidak Ada Semester Aktif' }}</h5>
                    <span class="badge badge-{{ $krsStatusClass }}">{{ $krsStatus }}</span>
                    <hr>
                    <a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-primary btn-block" wire:navigate>
                        <i class="fas fa-edit"></i> Buka Halaman KRS
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris Statistik Akademik --}}
    <div class="row">
        <div class="col-lg-4 col-6">
            <x-adminlte-small-box title="{{ $currentSemester }}" text="Semester Berjalan" icon="fas fa-calendar-alt text-dark"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-adminlte-small-box title="{{ number_format($ipk, 2) }}" text="IPK (Indeks Prestasi Kumulatif)" icon="fas fa-graduation-cap text-dark"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-adminlte-small-box title="{{ $totalSks }}" text="Total SKS Lulus" icon="fas fa-check-circle text-dark"/>
        </div>
    </div>
@stop