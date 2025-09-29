@extends('adminlte::page')
@section('title', 'Import Mahasiswa Lama')
@section('content_header')
    <h1>Import Mahasiswa Lama</h1>
@stop
@section('content')
   {{-- Tampilkan error validasi dari import --}}
@if (session('import_errors'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Import Gagal!</strong> Ada beberapa error pada data Anda:
        <ul>
            @foreach (session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('admin.akademik.students.import.old') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header"><h3 class="card-title">Formulir Upload</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label for="import_file">Pilih file Excel (.xlsx, .xls)</label>
                    <input type="file" name="import_file" class="form-control-file" id="import_file" required>
                </div>
                <p class="mt-3"><strong>Penting:</strong> Pastikan file Excel Anda memiliki kolom header sesuai template. 
                    {{-- <a href="#">Download Template</a> --}}
                </p>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Mulai Proses Import</button>
            </div>
        </form>
    </div>
@stop