@extends('adminlte::page')

@section('title', 'Detail Dosen')

@section('content_header')
    <h1>Detail Dosen: {{ $lecturer->name_with_degree ?? $lecturer->user->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($lecturer->user->profile_photo_path)
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset('storage/' . $lecturer->user->profile_photo_path) }}"
                                alt="User profile picture"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img class="profile-user-img img-fluid img-circle"
                                src="https://ui-avatars.com/api/?name={{ urlencode($lecturer->user->name) }}"
                                alt="User profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $lecturer->user->name }}</h3>
                    <p class="text-muted text-center">NIDN: {{ $lecturer->nidn }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $lecturer->user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Prodi</b> <a class="float-right">{{ $lecturer->program->name_id ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status Kepegawaian</b> <a class="float-right">{{ $lecturer->employmentStatus->name ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status Aktif</b> <a class="float-right">{{ ucfirst($lecturer->status) }}</a>
                        </li>
                        @if($lecturer->functional_position)
                        <li class="list-group-item">
                            <b>Jabatan Fungsional</b> <a class="float-right">{{ $lecturer->functional_position }}</a>
                        </li>
                        @endif
                        <li class="list-group-item">
                            <b>Jenis Kelamin</b> <a class="float-right">{{ $lecturer->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>No. HP/WA</b> <a class="float-right">{{ $lecturer->phone ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Tempat, Tanggal Lahir</b>
                            <a class="float-right">
                                {{ $lecturer->birth_place ? $lecturer->birth_place . ', ' : '' }}
                                {{ $lecturer->birth_date ? \Carbon\Carbon::parse($lecturer->birth_date)->format('d M Y') : '-' }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Rekening</b> <br>
                            <span class="text-muted">
                                {{ $lecturer->bank_name ?? '-' }} - {{ $lecturer->account_number ?? '-' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat</b> <br>
                            <span class="text-muted">{{ $lecturer->address ?? '-' }}</span>
                        </li>
                    </ul>

                    <a href="{{ route('admin.sdm.lecturers.edit', $lecturer->id) }}" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
             {{-- Livewire Component for Tabs --}}
            @livewire('sdm.employee-profile-tabs', ['employee' => $lecturer])
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra CSS here --}}
@stop

@section('js')
    {{-- Add any extra JS here --}}
@stop
