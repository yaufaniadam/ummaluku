@extends('adminlte::page')

@section('title', 'Detail Dosen')

@section('content_header')
    <h1>Detail Dosen: {{ $lecturer->full_name_with_degree ?? $lecturer->user->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i>Data Pribadi</h3>
            <div class="card-tools">
                <a href="{{ route('admin.sdm.lecturers.edit', $lecturer->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    @if($lecturer->user->profile_photo_path)
                        <img src="{{ asset('storage/' . $lecturer->user->profile_photo_path) }}"
                             alt="Profile Photo"
                             class="img-fluid"
                             style="max-width: 150px; border-radius: 8px; border: 3px solid #ddd;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($lecturer->user->name) }}&size=150"
                             alt="Profile Photo"
                             class="img-fluid"
                             style="max-width: 150px; border-radius: 8px; border: 3px solid #ddd;">
                    @endif
                </div>
                <div class="col-md-10">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="25%" style="font-weight: 600;">NIDN</td>
                                <td width="25%">{{ $lecturer->nidn }}</td>
                                <td width="25%" style="font-weight: 600;">Email</td>
                                <td width="25%">{{ $lecturer->user->email }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Nama</td>
                                <td>{{ $lecturer->full_name_with_degree ?? $lecturer->user->name }}</td>
                                <td style="font-weight: 600;">Email Universitas</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Gelar Depan</td>
                                <td>{{ $lecturer->front_degree ?? '-' }}</td>
                                <td style="font-weight: 600;">NIP</td>
                                <td>{{ $lecturer->nip ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Gelar Belakang</td>
                                <td>{{ $lecturer->back_degree ?? '-' }}</td>
                                <td style="font-weight: 600;">Jabatan Struktural</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Jenis Kelamin</td>
                                <td>{{ $lecturer->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td style="font-weight: 600;">Jabatan Fungsional</td>
                                <td>{{ $lecturer->functional_position ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Agama</td>
                                <td>-</td>
                                <td style="font-weight: 600;">Status Kepegawaian</td>
                                <td>{{ $lecturer->employmentStatus->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Golongan Darah</td>
                                <td>-</td>
                                <td style="font-weight: 600;">Status Aktif</td>
                                <td>
                                    <span class="badge badge-{{ $lecturer->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($lecturer->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Tempat Lahir</td>
                                <td>{{ $lecturer->birth_place ?? '-' }}</td>
                                <td style="font-weight: 600;">Prodi</td>
                                <td>{{ $lecturer->program->name_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Tanggal Lahir</td>
                                <td>{{ $lecturer->birth_date ? \Carbon\Carbon::parse($lecturer->birth_date)->format('d-m-Y') : '-' }}</td>
                                <td style="font-weight: 600;">No. Handphone</td>
                                <td>{{ $lecturer->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Alamat</td>
                                <td colspan="3">{{ $lecturer->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">NBM</td>
                                <td>-</td>
                                <td style="font-weight: 600;">NUPTK</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">NPWP</td>
                                <td>-</td>
                                <td style="font-weight: 600;">Rekening</td>
                                <td>{{ $lecturer->bank_name ?? '-' }} - {{ $lecturer->account_number ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Section Below Profile --}}
    <div class="mt-3">
        @livewire('sdm.employee-profile-tabs', ['employee' => $lecturer])
    </div>
@stop

@section('css')
    <style>
        .table-borderless td {
            padding: 0.5rem 0.75rem !important;
            border: none !important;
        }
    </style>
@stop

@section('js')
    {{-- Add any extra JS here --}}
@stop
