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
                        <dt class="col-5">Jenis Kelamin:</dt>
                        <dd class="col-7">{{ $application->prospective->gender }}</dd>
                        <dt class="col-5">Tempat, Tanggal Lahir:</dt>
                        <dd class="col-7">{{ $application->prospective->birth_place }},
                            {{ \Carbon\Carbon::parse($application->prospective->birth_date)->format('d F Y') }}
                        </dd>
                        <dt class="col-5">Agama:</dt>
                        <dd class="col-7">{{ $application->prospective->religion->id ?? '-' }}</dd>
                        <dt class="col-5">Kewarganegaraan:</dt>
                        <dd class="col-7">{{ $application->prospective->citizenship ?? '-' }}</dd>
                        <dt class="col-5">NIK:</dt>
                        <dd class="col-7">{{ $application->prospective->id_number }}</dd>
                        <dt class="col-5">Email:</dt>
                        <dd class="col-7">{{ $application->prospective->user->email }}</dd>
                        <dt class="col-5">No. Telepon:</dt>
                        <dd class="col-7">{{ $application->prospective->phone }}</dd>

                        <dt class="col-5">Alamat:</dt>
                        <dd class="col-7">
                            {{ $application->prospective->address ?? '-' }}<br>
                            Desa {{ ucwords(strtolower($application->prospective->village->id)) }},
                            Kecamatan {{ ucwords(strtolower($application->prospective->district->id)) }},
                            {{ ucwords(strtolower($application->prospective->city->id)) }},
                            {{ ucwords(strtolower($application->prospective->province->id)) }}

                        </dd>
                        <dt class="col-5">Asal Sekolah:</dt>
                        <dd class="col-7">{{ $application->prospective->highSchool->id }}</dd>
                        <dt class="col-5">NISN:</dt>
                        <dd class="col-7">{{ $application->prospective->nisn }}</dd>
                        <dt class="col-5">Penerima KPS:</dt>
                        <dd class="col-7">{{ $application->prospective->is_kps_recipient ? 'Ya' : 'Tidak' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Verifikasi Dokumen Persyaratan</h3>
                </div>
                <div class="card-body">
                    @livewire('admin.pendaftaran.document-manager', ['application' => $application], key($application->id))
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
                        <dt class="col-5">Penghasilan Ayah:</dt>
                        <dd class="col-7">Rp{{ number_format($application->prospective->father_income) ?? '-' }}</dd>
                        <dt class="col-5">Nama Ibu:</dt>
                        <dd class="col-7">{{ $application->prospective->mother_name ?? '-' }}</dd>
                        <dt class="col-5">Pekerjaan Ibu:</dt>
                        <dd class="col-7">{{ $application->prospective->mother_occupation ?? '-' }}</dd>
                        <dt class="col-5">Penghasilan Ibu:</dt>
                        <dd class="col-7">Rp{{ number_format($application->prospective->mother_income) ?? '-' }}</dd>
                        <dt class="col-5">No. Telepon Orang Tua:</dt>
                        <dd class="col-7">{{ $application->prospective->parent_phone ?? '-' }}</dd>
                    </dl>

                    {{-- Tampilkan bagian Wali hanya jika datanya diisi --}}
                    @if ($application->prospective->with_guardian)
                        <dl class="row">
                            <dt class="col-5">Nama Wali:</dt>
                            <dd class="col-7">{{ $application->prospective->guardian_name ?? '-' }}</dd>
                            <dt class="col-5">No. Telepon Wali:</dt>
                            <dd class="col-7">{{ $application->prospective->guardian_phone ?? '-' }}</dd>
                            <dt class="col-5">Pekerjaan Wali:</dt>
                            <dd class="col-7">{{ $application->prospective->guardian_occupation ?? '-' }}
                            <dt class="col-5">Pendapatan Wali:</dt>
                            <dd class="col-7">Rp{{ number_format($application->prospective->guardian_income) ?? '-' }}
                            </dd>
                        </dl>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-primary">Kembali</a>
                    @if ($application->status == 'proses_verifikasi')
                        {{-- Tombol terima --}}
                        <button class="btn btn-warning" wire:click="finalizeVerification"
                            wire:confirm="Anda yakin semua dokumen sudah diperiksa dan pendaftar ini lolos ke tahap seleksi?">
                            Loloskan Verifikasi</button>

                        {{-- Tombol tolak --}}
                        <button class="btn btn-danger" onclick="promptForApplicationRejection()">
                            Tolak Pendaftaran
                        </button>
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
    // ... fungsi promptForRevision dan listener show-alert sudah ada ...

    // Fungsi baru untuk memunculkan prompt penolakan aplikasi
    function promptForApplicationRejection() {
        Swal.fire({
            title: 'Tolak Pendaftaran Ini?',
            input: 'textarea',
            inputPlaceholder: 'Tuliskan alasan penolakan di sini (wajib diisi)...',
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus mengisi alasan penolakan!'
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak Pendaftaran',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Panggil method Livewire dengan alasan dari SweetAlert
                @this.call('rejectApplication', result.value);
            }
        });
    }
</script>
@endpush