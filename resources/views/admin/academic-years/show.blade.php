@extends('adminlte::page')
@section('title', 'Kelola Semester')
@section('content_header')
    <h1 class="mb-1">Kelola Semester</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.akademik.academic-years.index') }}" wire:navigate>Tahun Ajaran</a> > {{ $academicYear->name }}
    </h5>
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pilih Program Studi untuk Dikelola</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Program Studi</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programs as $program)
                        <tr>
                            <td>{{ $program->name_id }}</td>
                            <td>
                                {{-- Link ini akan kita perbaiki di langkah berikutnya --}}
                                <a href="{{ route('admin.akademik.academic-years.programs.course-classes.index', ['academic_year' => $academicYear, 'program' => $program]) }}"
                                    class="btn btn-primary btn-sm" wire:navigate>
                                    Kelola Kelas
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
