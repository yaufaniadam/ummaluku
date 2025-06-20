@extends('adminlte::page')

@section('title', 'Detail Pendaftar')

@section('content_header')
    <h1>Detail Pendaftar</h1>
    <div class="text-muted mt-1">{{ $application->registration_number }}</div>
@stop

@if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@section('content')


    <div class="row">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Biodata Lengkap</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Nama Lengkap:</dt>
                        <dd class="col-7">{{ $application->prospective->user->name }}</dd>
                        <dt class="col-5">Email:</dt>
                        <dd class="col-7">{{ $application->prospective->user->email }}</dd>
                        <dt class="col-5">No. Telepon:</dt>
                        <dd class="col-7">{{ $application->prospective->phone }}</dd>
                        <dt class="col-5">NIK:</dt>
                        <dd class="col-7">{{ $application->prospective->id_number }}</dd>
                        <dt class="col-5">NISN:</dt>
                        <dd class="col-7">{{ $application->prospective->nisn }}</dd>
                        <dt class="col-5">Jenis Kelamin:</dt>
                        <dd class="col-7">{{ $application->prospective->gender }}</dd>
                        <dt class="col-5">Tempat, Tanggal Lahir:</dt>
                        <dd class="col-7">{{ $application->prospective->birth_place }},
                            {{ \Carbon\Carbon::parse($application->prospective->birth_date)->format('d F Y') }}
                        </dd>
                        <dt class="col-5">Agama:</dt>
                        <dd class="col-7">{{ $application->prospective->religion->name ?? '-' }}</dd>
                        <dt class="col-5">Alamat:</dt>
                        <dd class="col-7">{{ $application->prospective->address }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Verifikasi Dokumen Persyaratan</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Nama Dokumen</th>
                                <th>Status</th>
                                <th>File Diunggah</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop semua dokumen yang disyaratkan untuk jalur pendaftaran ini --}}
                            @foreach ($application->admissionCategory->documentRequirements as $requirement)
                                @php
                                    // Cek apakah pendaftar sudah mengupload dokumen ini
                                    $uploadedDocument = $application->documents->firstWhere(
                                        'document_requirement_id',
                                        $requirement->id,
                                    );
                                @endphp
                                <tr>
                                    <td>{{ $requirement->name }}</td>
                                    <td>
                                        @if ($uploadedDocument)
                                            @if ($uploadedDocument->status == 'verified')
                                                <span class="badge bg-success me-1"></span> Terverifikasi
                                            @elseif($uploadedDocument->status == 'revision_needed')
                                                <span class="badge bg-warning me-1"></span> Perlu Revisi
                                            @elseif($uploadedDocument->status == 'rejected')
                                                <span class="badge bg-danger me-1"></span> Ditolak
                                            @else
                                                <span class="badge bg-info me-1"></span> Menunggu Verifikasi
                                            @endif
                                        @else
                                            <span class="badge bg-secondary me-1"></span> Belum Diunggah
                                        @endif
                                    </td>
                                    <td>
                                        @if ($uploadedDocument)
                                            <a href="{{ Storage::url($uploadedDocument->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-info">Lihat File</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($uploadedDocument)
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="verifyDocument({{ $uploadedDocument->id }})"
                                                    wire:confirm="Anda yakin ingin MENYETUJUI dokumen ini?">
                                                    Setujui
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="rejectDocument({{ $uploadedDocument->id }})"
                                                    wire:confirm="Anda yakin ingin MENOLAK dokumen ini?">
                                                    Tolak
                                                </button>
                                                <button class="btn btn-sm btn-warning"
                                                    onclick="promptForRevision({{ $uploadedDocument->id }})">
                                                    Revisi
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Data Orang Tua / Wali</h3>
                </div>
                <div class="card-body">

                    <dl class="row">
                        <dt class="col-5">Nama Ayah:</dt>
                        <dd class="col-7">{{ $application->prospective->father_name ?? '-' }}</dd>
                        <dt class="col-5">Pekerjaan Ayah:</dt>
                        <dd class="col-7">{{ $application->prospective->father_occupation ?? '-' }}</dd>
                        <dt class="col-5">Nama Ibu:</dt>
                        <dd class="col-7">{{ $application->prospective->mother_name ?? '-' }}</dd>
                        <dt class="col-5">Pekerjaan Ibu:</dt>
                        <dd class="col-7">{{ $application->prospective->mother_occupation ?? '-' }}</dd>
                        <dt class="col-5">No. Telepon Orang Tua:</dt>
                        <dd class="col-7">{{ $application->prospective->parent_phone ?? '-' }}</dd>
                    </dl>

                    {{-- Tampilkan bagian Wali hanya jika datanya diisi --}}
                    @if ($application->prospective->guardian_name)
                        <dl class="row">
                            <dt class="col-5">Nama Wali:</dt>
                            <dd class="col-7">{{ $application->prospective->guardian_name ?? '-' }}</dd>
                            <dt class="col-5">No. Telepon Wali:</dt>
                            <dd class="col-7">{{ $application->prospective->guardian_phone ?? '-' }}</dd>
                            <dt class="col-5">Pekerjaan Wali:</dt>
                            <dd class="col-7">{{ $application->prospective->guardian_occupation ?? '-' }}
                            </dd>
                        </dl>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                {{-- <div class="btn-list">
                    <a href="" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l14 0"></path>
                            <path d="M5 12l4 4"></path>
                            <path d="M5 12l-4 -4"></path>
                        </svg>
                        Kembali
                    </a>
                    <a href="#" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 12l5 5l10 -10"></path>
                        </svg>
                        Verifikasi Pendaftaran
                    </a>
                </div> --}}


                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-primary">Kembali</a>
                    @if ($application->status == 'awaiting_verification')
                        <button class="btn btn-warning" wire:click="advanceToSelection"
                            wire:confirm="Anda yakin semua dokumen sudah diperiksa dan pendaftar ini lolos ke tahap seleksi?">

                            Loloskan ke Tahap Seleksi</button>
                    @endif
                </div>



            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Pendaftaran</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Status:</dt>
                        <dd class="col-7"><span class="badge bg-warning me-1"></span>
                            {{ Str::title(str_replace('_', ' ', $application->status)) }}</dd>
                        <dt class="col-5">Jalur:</dt>
                        <dd class="col-7">{{ $application->admissionCategory->name }}</dd>
                        <dt class="col-5">Gelombang:</dt>
                        <dd class="col-7">{{ $application->batch->name }} ({{ $application->batch->year }})
                        </dd>
                        <dt class="col-5">Tanggal Daftar:</dt>
                        <dd class="col-7">{{ $application->created_at->format('d M Y, H:i') }}</dd>
                    </dl>
                    <hr>
                    <h4 class="mb-3">Pilihan Program Studi</h4>
                    @foreach ($application->programChoices as $choice)
                        <p>
                            <strong>Pilihan {{ $choice->choice_order }}:</strong><br>
                            {{ $choice->program->name_id }} ({{ $choice->program->degree }})
                        </p>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@stop

@push('js')
    <script>
        // Fungsi untuk prompt revisi, ini tetap sama
        function promptForRevision(documentId) {
            const notes = prompt('Silakan masukkan catatan revisi untuk dokumen ini:');
            if (notes != null && notes != "") {
                @this.call('requireRevision', documentId, notes);
            }
        }

        document.addEventListener('show-alert', event => {
            Swal.fire({
                title: event.detail.type === 'success' ? 'Berhasil!' : 'Oops!',
                text: event.detail.message,
                icon: event.detail.type, // 'success' atau 'error'
                confirmButtonText: 'Oke'
            });
        });
    </script>
@endpush
