@extends('adminlte::page')

@section('title', 'Manajemen Dosen')

@section('content_header')
    <h1>Manajemen Dosen</h1>
@stop

@section('content')
    <livewire:admin.lecturer.actions />
    <div x-data @lecturer-updated.window="window.LaravelDataTables['lecturer-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Dosen</h3>
                <div class="card-tools">

                   <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal">
    Import Dosen
</button>
                    <a href="{{ route('admin.sdm.lecturers.create') }}" class="btn btn-primary btn-sm" wire:navigate>Tambah Dosen
                        Baru</a>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.sdm.lecturers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="import_file">Pilih file Excel (.xlsx, .xls)</label>
                        <input type="file" name="import_file" class="form-control-file" id="import_file" required>
                    </div>
                    <p class="mt-3">
                        <strong>Penting:</strong> Pastikan file Excel Anda memiliki kolom header berikut (huruf kecil dan tanpa spasi):<br>
                        <code>nidn</code>, <code>nama_lengkap_dengan_gelar</code>, <code>email</code>, <code>program_studi_id</code>.
                    </p>

                     <p><a href="{{ asset('template/dosen_import.xlsx') }}" target="_blank" rel="noopener noreferrer">Download template</a>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

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

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
