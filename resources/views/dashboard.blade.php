{{-- Menggunakan layout master dari paket Laravel-AdminLTE --}}
@extends('adminlte::page')

{{-- Mengatur Judul Halaman --}}
@section('title', 'Data Pendaftar')

{{-- Mengatur Judul Konten (Header) --}}
@section('content_header')
    <h1>Dashboard</h1>
@stop

{{-- Mengisi Konten Utama Halaman --}}
@section('content')
    
    <div class="card">
        <div class="card-body">
          Dasborad di sini
        </div>
    </div>
@stop

{{-- (Opsional) Menambahkan CSS kustom --}}
@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

{{-- (Opsional) Menambahkan JavaScript kustom --}}
@section('js')
    <script> console.log('Halaman Data Pendaftar dimuat!'); </script>
@stop