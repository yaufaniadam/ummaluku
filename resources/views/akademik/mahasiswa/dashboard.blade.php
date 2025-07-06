@extends('adminlte::page')

@section('title', 'Dashboard Mahasiswa')

@section('content_header')
    <h1>Dashboard Mahasiswa</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $student->photo_path ? Storage::url($student->photo_path) : asset('assets/user.png') }}"
                             alt="Foto profil mahasiswa">
                    </div>

                    <h3 class="profile-username text-center">{{ $student->user->name }}</h3>

                    <p class="text-muted text-center">{{ $student->program->name_id }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NIM</b> <a class="float-right">{{ $student->nim }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Tahun Masuk</b> <a class="float-right">{{ $student->entry_year }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> <a class="float-right"><span class="badge badge-success">{{ $student->status }}</span></a>
                        </li>
                    </ul>

                    {{-- Tombol Aksi --}}
                    {{-- <a href="#" class="btn btn-primary btn-block"><b>Lihat Kartu Hasil Studi (KHS)</b></a> --}}
                </div>
            </div>
        </div>
    </div>
@stop