@extends('adminlte::page')

@section('title', 'Dashboard Dosen')

@section('content_header')
    <h1>Dashboard Dosen</h1>
@stop

@section('content')
    {{-- Baris untuk Statistik Cepat --}}
    <div class="row">
        <div class="col-lg-4 col-6">
            <x-adminlte-small-box title="{{ $pendingKrsCount }}" text="Persetujuan KRS Tertunda" icon="fas fa-user-clock text-warning"
                url="{{ route('dosen.krs-approval.index') }}" url-text="Lihat Detail"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-adminlte-small-box title="{{ $totalClassesTaught }}" text="Kelas Diampu Semester Ini" icon="fas fa-chalkboard-teacher text-info"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-adminlte-small-box title="{{ $totalAdvisedStudents }}" text="Total Mahasiswa Bimbingan" icon="fas fa-users text-success"/>
        </div>
    </div>

    {{-- Baris untuk Daftar & Informasi --}}
    <div class="row">
        <div class="col-md-8">
            {{-- Card untuk Daftar Persetujuan KRS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Persetujuan KRS Tertunda</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $pendingKrsCount }} Mahasiswa</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            @forelse ($pendingKrsStudents as $student)
                                <tr>
                                    <td>{{ $student->nim }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td class="text-right">
                                        {{-- Tombol ini akan kita arahkan ke halaman detail nanti --}}
                                        <a href="#" class="btn btn-xs btn-primary">Proses</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada permintaan persetujuan KRS saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('dosen.krs-approval.index') }}">Lihat Semua Persetujuan</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            {{-- Card untuk Kelas yang Diampu --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelas yang Diampu Semester Ini</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($classesTaught as $class)
                            <li class="list-group-item">
                                <strong>{{ $class->course->name }}</strong> - Kelas {{ $class->name }}
                                {{-- <br> --}}
                                {{-- <small class="text-muted">{{ $class->day ?? '' }}, {{ $class->start_time ? date('H:i', strtotime($class->start_time)) : '' }} - {{ $class->end_time ? date('H:i', strtotime($class->end_time)) : '' }}</small> --}}
                            </li>
                        @empty
                            <li class="list-group-item text-center">Anda tidak mengampu kelas apapun semester ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop