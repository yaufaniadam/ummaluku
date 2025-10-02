@extends('adminlte::page')

@section('title', 'Dashboard Mahasiswa')

@section('content_header')
    <h1>Dashboard Mahasiswa</h1>
@stop

@section('content')

    @if (!$isProfileComplete)
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
            Biodata Anda belum lengkap. Mohon luangkan waktu untuk melengkapi profil Anda agar data akademik Anda valid.
            <a href="{{ route('mahasiswa.profil.index') }}" class="btn btn-sm btn-primary ml-3" wire:navigate>Lengkapi Profil
                Sekarang</a>
        </div>
    @endif

    {{-- Baris Profil & Status KRS --}}

    {{-- Widget Profil --}}
    <x-adminlte-profile-widget name="{{ $student->user->name }}" desc="{{ $student->user->student->nim }}" layoutType="modern"
        img="{{ $student->user->prospective->photo_path ? Storage::url($student->user->prospective->photo_path) : asset('assets/user.png') }}">
        <x-adminlte-profile-col-item class="text-primary border-right" icon="fas fa-fw fa-book" title="Program Studi"
            text="{{ $student->program->name_id }}" size=6 />
        <x-adminlte-profile-col-item class="text-dark" icon="fas fa-fw fa-user-tie" title="Dosen Wali Akademik"
            text="{{ $student->academicAdvisor->full_name_with_degree ?? 'Belum Diatur' }}" size=6 />
    </x-adminlte-profile-widget>


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Status KRS Semester Ini</h3>
        </div>
        <div class="card-body">
            <h5>{{ $activeSemester->name ?? 'Tidak Ada Semester Aktif' }}</h5>             
        </div>
         <div class="card-footer">
            <div class="float-left">
               <span class="badge badge-{{ $krsStatusClass }}">{{ $krsStatus }}</span>
            </div>
            <div class="float-right">
             <a href="{{ route('mahasiswa.krs.proses') }}" class="btn btn-info btn-block" wire:navigate>
                <i class="fas fa-edit"></i> Buka Halaman KRS
            </a>
            </div>
        </div>
    </div>

    {{-- Baris Statistik Akademik --}}
    <div class="row">
        <div class="col-lg-4 col-6">
            <x-stat-box title="Semester Berjalan" value="{{ $currentSemester }}" icon="fas fa-user-graduate" color="info"
                url="" />
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="IPK (Indeks Prestasi Kumulatif)" value="{{ number_format($ipk, 2) }}"
                icon="fas fa-user-graduate" color="warning" url="" />
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Total SKS" value="{{ $totalSks }}" icon="fas fa-user-graduate" color="success"
                url="" />
        </div>
    </div>
@stop
