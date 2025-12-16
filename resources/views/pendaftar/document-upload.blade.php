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
                    <h5 class="card-title">Selamat Datang, {{ $application->prospective->user->name }}!</h5>
                    <small class="text-muted">No. {{ $application->registration_number }}</small><br>
                    <span>Status: <strong>{{ Str::title(str_replace('_', ' ', $application->status)) }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">

         <x-breadcrumb current="{{ $application->status }}" />
       

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Upload Dokumen Persyaratan</h4>
                <small class="text-muted"><i class="bi bi-exclamation-triangle"></i> Upload dokumen satu-persatu. Jenis file
                    JPG/JPEG/PDF maksimal 1 MB</small>
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
                                @php
                                    $isReadOnly = in_array($application->status, ['diterima', 'sudah_registrasi']);
                                @endphp

                                @if ($isReadOnly)
                                    @if ($uploadedDocument)
                                        <a href="{{ Storage::url($uploadedDocument->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-secondary">
                                            <i class="bi bi-eye"></i> Lihat File
                                        </a>
                                        <span class="ms-2 text-muted fst-italic"><small>Dokumen terkunci</small></span>
                                    @else
                                        <span class="text-muted"><small>Tidak ada dokumen yang diunggah.</small></span>
                                    @endif
                                @else
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
