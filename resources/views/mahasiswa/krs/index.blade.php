@extends('adminlte::page')

@section('title', 'KRS Aktif')

@section('content_header')
    <h1>Kartu Rencana Studi (KRS) Semester Ini</h1>
@stop

@section('content')

    <div class="callout callout-danger">
        <h5>Keterangan :</h5>
        <p> Fasilitas KRS Online ini hanya dapat digunakan pada saat masa KRS atau masa revisi KRS. Mahasiswa dapat memilih
            matakuliah yang ingin diambil bersesuaian dengan jatah sks yang dimiliki dan matakuliah yang ditawarkan. </p>
    </div>

    @if (!$activeSemester)
        <div class="alert alert-warning">Tidak ada semester akademik yang sedang aktif.</div>
    @elseif($enrollments->isEmpty())
        <div class="alert alert-info">
            Anda belum memiliki KRS yang disetujui untuk semester <strong>{{ $activeSemester->name }}</strong>.
            Silakan lengkapi di menu <a href="{{ route('mahasiswa.krs.proses') }}">Pengisian KRS</a>.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">KRS Semester: {{ $activeSemester->name }}</h3>
                <div class="card-tools">
                    {{-- Status Pembayaran --}}
                    @if ($invoice && $invoice->status == 'paid')
                        <span class="badge badge-success">Pembayaran Lunas</span>
                    @else
                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            {{-- <th>Kelas</th> --}}
                            <th>Dosen Pengampu</th>
                            {{-- <th>Jadwal</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->courseClass->course->code }}</td>
                                <td>{{ $enrollment->courseClass->course->name }}</td>
                                <td>{{ $enrollment->courseClass->course->sks }}</td>
                                {{-- <td>{{ $enrollment->courseClass->name }}</td> --}}
                                <td>{{ $enrollment->courseClass->lecturer?->full_name_with_degree ?? 'TBA' }}</td>
                                {{-- <td>{{ $enrollment->courseClass->day ?? '-' }}, {{ $enrollment->courseClass->start_time ? date('H:i', strtotime($enrollment->courseClass->start_time)) : '-' }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('mahasiswa.krs.aktif.print') }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-print"></i> Cetak KRS
                </a>
            </div>
        </div>
    @endif
@stop
