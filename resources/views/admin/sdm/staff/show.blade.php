@extends('adminlte::page')

@section('title', 'Detail Staf')

@section('content_header')
    <h1>Detail Staf: {{ $staff->user->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="https://ui-avatars.com/api/?name={{ urlencode($staff->user->name) }}"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ $staff->user->name }}</h3>
                    <p class="text-muted text-center">NIP: {{ $staff->nip }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $staff->user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Unit Kerja</b>
                            <a class="float-right">
                                {{ $staff->program ? $staff->program->name_id : ($staff->workUnit ? $staff->workUnit->name : '-') }}
                            </a>
                        </li>
                    </ul>

                    <a href="{{ route('admin.sdm.staff.edit', $staff->id) }}" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            {{-- Livewire Component for Tabs --}}
            @livewire('sdm.employee-profile-tabs', ['employee' => $staff])
        </div>
    </div>
@stop
