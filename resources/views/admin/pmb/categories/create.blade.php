@extends('adminlte::page')
@section('title', 'Tambah Jalur Pendaftaran')
@section('content_header')
    <h1>Tambah Jalur Pendaftaran</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('admin.pmb.jalur-pendaftaran.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.pmb.categories._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.pmb.jalur-pendaftaran.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@stop