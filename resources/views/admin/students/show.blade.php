@extends('adminlte::page')
@section('title', 'Detail Mahasiswa')
@section('content_header')
    <h1 class="mb-1">Detail Mahasiswa</h1>
    <h5 class="font-weight-light">
        {{-- Breadcrumb bisa disesuaikan dari halaman mana user datang --}}
        <a href="{{ url()->previous() }}">Kembali</a> > {{ $student->user->name }}
    </h5>
@stop
@section('content')
    <div class="row">
        <div class="col-md-4">
            {{-- Card Profil Utama --}}
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="https://picsum.photos/id/1/128"
                            alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ $student->user->name }}</h3>
                    <p class="text-muted text-center">{{ $student->program->name_id }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NIM</b> <a class="float-right">{{ $student->nim }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Angkatan</b> <a class="float-right">{{ $student->entry_year }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> <a class="float-right"><span
                                    class="badge badge-success">{{ Str::title($student->status) }}</span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Dosen PA</b> <a
                                class="float-right">{{ $student->academicAdvisor->full_name_with_degree ?? 'Belum Diatur' }}</a>
                        </li>
                    </ul>
                    @if (auth()->user()->hasRole(['Super Admin', 'Staf Akademik']))
                        <a href="{{ route('admin.akademik.students.edit', $student->id) }}"
                            class="btn btn-primary btn-block" wire:navigate><b>Edit Data</b></a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Card dengan Tab --}}
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#biodata" data-toggle="tab">Biodata</a></li>
                        <li class="nav-item"><a class="nav-link" href="#riwayat_krs" data-toggle="tab">Riwayat Studi
                                (KRS)</a></li>
                        <li class="nav-item"><a class="nav-link" href="#transkrip" data-toggle="tab">Transkrip (Coming
                                Soon)</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="biodata">
                            <strong><i class="fas fa-book mr-1"></i> Data Diri</strong>
                            <p class="text-muted">
                                NIK: {{ $student->user->prospective->id_number ?? '-' }} <br>
                                Tempat, Tanggal Lahir: {{ $student->user->prospective->birth_place ?? '-' }},
                                {{ $student->user->prospective->birth_date ? \Carbon\Carbon::parse($student->user->prospective->birth_date)->isoFormat('D MMMM YYYY') : '-' }}
                                <br>
                                Jenis Kelamin: {{ $student->user->prospective->gender ?? '-' }}
                            </p>
                            <hr>
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                            <p class="text-muted">{{ $student->user->prospective->address ?? 'Belum diisi' }}</p>
                            <hr>
                            <strong><i class="fas fa-users mr-1"></i> Data Orang Tua</strong>
                            <p class="text-muted">
                                Nama Ayah: {{ $student->user->prospective->father_name ?? '-' }} <br>
                                Nama Ibu: {{ $student->user->prospective->mother_name ?? '-' }}
                            </p>
                        </div>

                        <div class="tab-pane" id="riwayat_krs">
                            {{-- Kita akan buat tabel riwayat KHS per semester di sini nanti --}}
                            <p>Fitur Riwayat Studi (KHS) akan segera hadir.</p>
                        </div>

                        <div class="tab-pane" id="transkrip">
                            <p>Fitur Transkrip Nilai akan segera hadir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
