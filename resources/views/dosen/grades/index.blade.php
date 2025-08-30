@extends('adminlte::page')

@section('title', 'Input Nilai')

@section('content_header')
    <h1>Input Nilai Perkuliahan</h1>
@stop

@section('content')
    @if (!$activeSemester)
        <div class="alert alert-warning">
            Saat ini tidak ada semester akademik yang aktif.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelas yang Diampu - {{ $activeSemester->name }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Mata Kuliah</th>
                            <th>Kelas</th>
                            <th>Jumlah Mahasiswa (KRS Disetujui)</th>
                            <th style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $key => $class)
                            <tr>
                                <td>{{ $key + 1 }}.</td>
                                <td>{{ $class->course->code }} - {{ $class->course->name }}</td>
                                <td>{{ $class->name }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $class->enrollments_count }} Mahasiswa</span>
                                </td>
                                <td>
                                    {{-- Tombol ini akan kita fungsikan di langkah berikutnya --}}
                                    <a href="#" class="btn btn-primary btn-sm">Input Nilai</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Anda tidak mengampu kelas apapun pada semester ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop