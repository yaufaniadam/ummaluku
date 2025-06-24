@extends('adminlte::page')
@section('title', 'Manajemen Jalur Pendaftaran')
@section('content_header')
    <h1>Manajemen Jalur Pendaftaran</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Jalur</h3>
            <div class="card-tools">
                <a href="{{ route('admin.jalur-pendaftaran.create') }}" class="btn btn-primary">Tambah Baru</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Jalur</th>
                        <th>Grup</th>
                        <th>Biaya (Rp)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->display_group }}</td>
                            <td>{{ number_format($category->price) }}</td>
                            <td>{{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                            <td>
                                <a href="{{ route('admin.jalur-pendaftaran.edit', $category) }}"
                                    class="btn btn-xs btn-warning">Edit</a>
                                <form action="{{ route('admin.jalur-pendaftaran.destroy', $category) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    {{-- <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Anda yakin?')">Hapus</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
