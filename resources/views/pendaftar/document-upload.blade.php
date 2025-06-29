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
                    <h5 class="card-title">Selamat Datang, {{ $application->prospective->user->name }}!</h5 >
                    <small class="text-muted">No. {{ $application->registration_number }}</small><br>
                    <span>Status: {{ Str::title(str_replace('_', ' ', $application->status)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        {{-- Kita set stepper ke langkah 3 --}}
        <x-stepper current-step="3" step1-text="Pendaftaran Awal" step2-text="Lengkapi Biodata" step3-text="Upload Dokumen"
            step4-text="Selesai" />

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Dokumen Persyaratan</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger pb-0">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- PERBAIKAN DIMULAI DARI SINI --}}
                {{-- Kita memulai loop dari $application->admissionCategory->documentRequirements --}}
                @foreach ($application->admissionCategory->documentRequirements as $requirement)
                    @php
                        $uploadedDocument = $application->documents->firstWhere(
                            'document_requirement_id',
                            $requirement->id,
                        );
                    @endphp

                    <div class="row py-2 align-items-center">
                        {{-- Kolom Kiri: Nama Dokumen & Status --}}
                        <div class="col-md-6">
                            <p class="mb-1"><strong>{{ $requirement->name }}</strong></p>
                            <p class="text-muted mb-1">{{ $requirement->description }}</p>
                            <div>
                                @if ($uploadedDocument)
                                    @if ($uploadedDocument->status == 'verified')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @elseif($uploadedDocument->status == 'revision_needed')
                                        <span class="badge bg-danger">Perlu Direvisi</span>
                                    @else
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum Diunggah</span>
                                @endif
                            </div>
                            @if ($uploadedDocument && $uploadedDocument->status == 'revision_needed' && $uploadedDocument->notes)
                                <div class="text-danger mt-2" style="font-size: 0.8rem;">
                                    <strong>Catatan Revisi:</strong> {{ $uploadedDocument->notes }}
                                </div>
                            @endif
                        </div>

                        {{-- Kolom Kanan: Form Upload atau Tombol Lihat File --}}
                        <div class="col-md-6">
                            <div class="pt-2">
                                @if (!$uploadedDocument || $uploadedDocument->status == 'revision_needed')
                                    <form action="{{ route('pendaftar.document.store', $application->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="document_id" value="{{ $requirement->id }}">
                                        <div class="input-group">
                                            <input type="file" name="file_upload" class="form-control" required>
                                            <button type="submit" class="btn btn-outline-primary">Upload</button>
                                        </div>
                                    </form>
                                @else
                                    <a href="{{ Storage::url($uploadedDocument->file_path) }}" target="_blank"
                                        class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i> Lihat File
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- AKHIR DARI PERBAIKAN --}}

                {{-- <div class="mt-4 text-center">
                <p>Setelah semua dokumen diunggah, silakan tunggu proses verifikasi dari tim kami.</p>
                <a href="#" class="btn btn-primary btn-lg">Selesaikan Pendaftaran</a>
            </div> --}}

            </div>
        </div>
    </div>
@endsection
