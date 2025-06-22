@extends('adminlte::page')
@section('title', 'Edit Jalur Pendaftaran')
@section('content_header')
    <h1>Edit Jalur Pendaftaran: {{ $category->name }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('admin.pmb.jalur-pendaftaran.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.pmb.categories._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.pmb.jalur-pendaftaran.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@stop