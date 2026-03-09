@extends('adminlte::page')

@section('title', 'Profil Saya')

@section('content_header')
    <h1>Profil Saya</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($user->profile_photo_path)
                             <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                 alt="User profile picture">
                        @else
                            <img class="profile-user-img img-fluid img-circle"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                 alt="User profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">{{ $user->email }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                         @if($employee)
                            @if(class_basename($employee) === 'Lecturer')
                                <li class="list-group-item">
                                    <b>NIDN</b> <a class="float-right">{{ $employee->nidn }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Prodi</b> <a class="float-right">{{ $employee->program->name_id ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jabatan Fungsional</b> <a class="float-right">{{ $employee->functional_position ?? '-' }}</a>
                                </li>
                            @elseif(class_basename($employee) === 'Staff')
                                <li class="list-group-item">
                                    <b>NIP</b> <a class="float-right">{{ $employee->nip }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Unit Kerja</b>
                                    <a class="float-right">
                                        {{ $employee->program ? $employee->program->name_id : ($employee->workUnit ? $employee->workUnit->name : '-') }}
                                    </a>
                                </li>
                            @endif
                            <li class="list-group-item">
                                <b>Status</b> <a class="float-right">{{ $employee->employmentStatus->name ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>No. HP</b> <a class="float-right">{{ $employee->phone ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Rekening</b> <br>
                                <span class="float-right text-right">
                                    {{ $employee->bank_name ?? '-' }} <br>
                                    {{ $employee->account_number ?? '-' }}
                                </span>
                            </li>
                        @endif
                    </ul>

                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if($employee)
                {{-- Livewire Component for Tabs in Self Service Mode --}}
                @livewire('sdm.employee-profile-tabs', ['employee' => $employee, 'isSelfService' => true])
            @else
                <div class="alert alert-warning">
                    Data kepegawaian tidak ditemukan. Hubungi Administrator.
                </div>
            @endif
        </div>
    </div>
@stop
