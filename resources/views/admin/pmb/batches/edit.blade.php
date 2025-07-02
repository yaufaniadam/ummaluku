@extends('adminlte::page')

@section('title', 'Tambah Gelombang Baru')

@section('content_header')
    <h1>Tambah Gelombang Pendaftaran</h1>
@stop

@section('content')
    <div class="card">
        {{-- Arahkan form ke route store untuk menyimpan data baru --}}
        <form action="{{ route('admin.gelombang.update', $batch) }}" method="POST">
            @csrf
            @method('PUT')  {{-- supaya bisa submit --}}
            <div class="card-body">
                {{-- Panggil potongan form yang sudah kita buat --}}
                {{-- Kita tidak mengirim variabel $batch karena ini adalah form 'create' --}}
                @include('admin.pmb.batches._form')
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.gelombang.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@stop