@extends('adminlte::page')

@section('title', 'Kartu Hasil Studi')

@section('content_header')
    <h1>Kartu Hasil Studi (KHS) & Transkrip</h1>
@stop

@section('content')
    <div class="callout callout-danger">
        <h5>Keterangan :</h5>
        <p> Transkrip Nilai berisi informasi nilai hasil studi mahasiswa mulai dari semester awal sampai dengan
            semester terakhir mahasiswa.</p>
    </div>
    {{-- Card untuk Ringkasan Prestasi Akademik Keseluruhan --}}
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">Ringkasan Prestasi Akademik</h3>
        </div> --}}
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li><strong>Nama:</strong> {{ $student->user->name }}</li>
                        <li><strong>NIM:</strong> {{ $student->nim }}</li>
                        <li>
                            <strong>Program Studi:</strong> {{ $student->program->name_id }}
                        </li>
                        <li>
                            <strong>Angkatan:</strong> {{ $student->entry_year }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>
                            IP Kumulatif (IPK): <strong>{{ number_format($ipk, 2) }}</strong>
                        </li>
                        <li>
                            Jumlah SKS Diambil: <strong>{{ $totalSksTaken }}</strong>
                        </li>
                        <li>
                            Jumlah Mata Kuliah Diambil: <strong>{{ $totalCoursesTaken }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    @if ($enrollmentsBySemester->isEmpty())
        <div class="alert alert-warning">
            Belum ada riwayat studi yang bisa ditampilkan.
        </div>
    @else
        {{-- Tampilkan KHS per Semester --}}
        @foreach ($enrollmentsBySemester as $semesterName => $enrollments)
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">{{ $semesterName }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th style="width: 100px;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop berdasarkan mata kuliah yang BENAR-BENAR DIAMBIL pada semester itu --}}
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->courseClass->course->code }}</td>
                                    <td>{{ $enrollment->courseClass->course->name }}</td>
                                    <td>{{ $enrollment->courseClass->course->sks }}</td>
                                    <td class="text-center font-weight-bold">
                                        {{ $enrollment->grade_letter }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
@stop
