@extends('layouts.frontend')

@section('title', 'Upload Dokumen Persyaratan')

@section('content')

    <div class="page-header d-print-none pt-5 pb-5 bg-light">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    {{-- Judul Halaman Dinamis --}}
                    <h2 class="page-title">
                        {{ $application->admissionCategory->name }}
                    </h2>
                    <div class="text-muted mt-1">
                        {{ $application->batch->name }} - Tahun Ajaran {{ $application->batch->year }}
                    </div>

                    </p>
                </div>
                <div class="col-md-6 text-lg-end ">
                    <h5 class="card-title"> {{ $application->prospective->user->name }}!</h5>
                    <small class="text-muted">No. {{ $application->registration_number }}</small><br>
                    <span>Status: <strong>{{ Str::title(str_replace('_', ' ', $application->status)) }}</strong></span>
                </div>
            </div>
        </div>
    </div>



    <div class="container py-4">

        <x-breadcrumb current="{{ $application->status }}" />

        {{-- BLOK PENGUMUMAN KELULUSAN --}}
        @if ($application->status == 'diterima')
            @php
                $acceptedProgram = $application->programChoices->where('is_accepted', true)->first()->program;
            @endphp
            <div class="alert alert-success" role="alert">
                <h4 class="alert-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-confetti" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 5h2"></path>
                        <path d="M5 4v2"></path>
                        <path d="M11.5 4l-.5 2"></path>
                        <path d="M18 5h2"></path>
                        <path d="M19 4v2"></path>
                        <path d="M15 9l-1 1"></path>
                        <path d="M18 13l2 -.5"></path>
                        <path d="M18 19h2"></path>
                        <path d="M19 18v2"></path>
                        <path d="M14 16.518l-6.518 -6.518l-4.39 9.58a1.003 1.003 0 0 0 1.329 1.329l9.579 -4.39z"></path>
                    </svg>
                    Selamat! Anda Diterima!
                </h4>
                <div class="text-muted">
                    Anda telah diterima sebagai calon mahasiswa baru di Program Studi
                    <strong>{{ $acceptedProgram->name_id }}</strong>.
                    Silakan <a href="{{ route('pendaftar.registrasi') }}">klik di sini</a> untuk informasi selanjutnya
                    mengenai pembayaran dan registrasi ulang.
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h5 class="card-title mb-2">Selamat Datang, {{ $application->prospective->user->name }}!</h5>
        <p>Status Pendaftaran Anda saat ini: <strong
                class="badge bg-warning">{{ Str::title(str_replace('_', ' ', $application->status)) }}</strong>
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tr>
                    <td width="20%">Nama</td>
                    <td>{{ $application->prospective->user->name }}</td>
                </tr>
                <tr>
                    <td>Tempat/Tanggal lahir</td>
                    <td>{{ $application->prospective->birth_place }},
                        {{ \Carbon\Carbon::parse($application->prospective->birth_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Telepon</td>
                    <td>{{ $application->prospective->phone }}</td>
                </tr>
                <tr>
                    <td>Telepon Orang Tua</td>
                    <td>{{ $application->prospective->parent_phone }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $application->prospective->user->email }}</td>
                </tr>
                <tr>
                    <td>Program Studi Pilihan</td>
                    <td>
                        @foreach ($application->programChoices as $choice)
                            <p>
                                <strong>Pilihan {{ $choice->choice_order }}:</strong><br>
                                {{ $choice->program->name_id }} ({{ $choice->program->degree }})
                            </p>
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>


    </div>

@endsection
