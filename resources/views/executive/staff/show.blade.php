@extends('adminlte::page')

@section('title', 'Detail Staf')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Staf: {{ $staff->user->name }}</h1>
        <a href="{{ route('executive.staff.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($staff->user->profile_photo_path)
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset('storage/' . $staff->user->profile_photo_path) }}"
                                alt="User profile picture"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img class="profile-user-img img-fluid img-circle"
                                src="https://ui-avatars.com/api/?name={{ urlencode($staff->user->name) }}"
                                alt="User profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $staff->user->name }}</h3>
                    <p class="text-muted text-center">NIP: {{ $staff->nip }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $staff->user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Jenis Kelamin</b> <a class="float-right">{{ $staff->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>No. HP</b> <a class="float-right">{{ $staff->phone ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Tempat, Tanggal Lahir</b>
                            <a class="float-right">
                                {{ $staff->birth_place ? $staff->birth_place . ', ' : '' }}
                                {{ $staff->birth_date ? \Carbon\Carbon::parse($staff->birth_date)->format('d M Y') : '-' }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Rekening</b> <br>
                            <span class="text-muted">
                                {{ $staff->bank_name ?? '-' }} - {{ $staff->account_number ?? '-' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Status Kepegawaian</b> <a class="float-right">{{ $staff->employmentStatus->name ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Unit Kerja</b>
                            <a class="float-right">
                                {{ $staff->program ? $staff->program->name_id : ($staff->workUnit ? $staff->workUnit->name : '-') }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat</b> <br>
                            <span class="text-muted">{{ $staff->address ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            {{-- Livewire Component for Tabs --}}
            @livewire('sdm.employee-profile-tabs', ['employee' => $staff, 'isReadOnly' => true])
        </div>
    </div>
@stop
