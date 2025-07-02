@extends('adminlte::page')
@section('title', 'Manajemen Gelombang')
@section('content_header')
    <h1>Manajemen Gelombang Pendaftaran</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Gelombang</h3>
            <div class="card-tools">
                <a href="{{ route('admin.gelombang.create') }}" class="btn btn-primary">Tambah Gelombang Baru</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Gelombang</th>
                        <th>Tahun Ajaran</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                    <tr>
                        <td>{{ $batch->name }}</td>
                        <td>{{ $batch->year }}</td>
                        <td>{{ \Carbon\Carbon::parse($batch->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($batch->end_date)->format('d M Y') }}</td>
                        <td>{!! $batch->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            <a href="{{ route('admin.gelombang.edit', $batch) }}" class="btn btn-xs btn-warning">Edit</a>
                            <form action="{{ route('admin.gelombang.destroy', $batch) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop