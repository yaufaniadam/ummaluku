@extends('adminlte::page')

@section('title', 'Kelola Mata Kuliah')

@section('content_header')
    {{-- Judul dinamis menampilkan nama kurikulum --}}
    <h1 class="mb-1">Kelola Mata Kuliah</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.curriculums.index') }}" wire:navigate>Kurikulum</a> > {{ $curriculum->name }}
    </h5>
@stop

@section('content')
    <livewire:admin.course.actions />

    {{-- Notifikasi akan muncul di sini --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div x-data @course-updated.window="window.LaravelDataTables['course-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Mata Kuliah</h3>
                <div class="card-tools">

                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#importCoursesModal">
                        Import Mata Kuliah
                    </button>
                    {{-- Tombol ini akan mengarah ke form tambah MK --}}
                    <a href="{{ route('admin.curriculums.courses.create', $curriculum->id) }}"
                        class="btn btn-primary btn-sm" wire:navigate>
                        Tambah Mata Kuliah
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    {{-- Modal untuk Import Mata Kuliah --}}
<div class="modal fade" id="importCoursesModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Mata Kuliah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.curriculums.courses.import', $curriculum->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="import_file">Pilih file Excel (.xlsx, .xls)</label>
                        <input type="file" name="import_file" class="form-control-file" id="import_file" required>
                    </div>
                    <p class="mt-3">
                        <strong>Penting:</strong> Pastikan file Excel Anda memiliki kolom header berikut:<br>
                        <code>kode_mk</code>, <code>nama_mata_kuliah</code>, <code>sks</code>, <code>semester</code>, <code>jenis</code>.
                        
                    </p>
                    <p><a href="{{ asset('template/template_mk.xlsx') }}" target="_blank" rel="noopener noreferrer">Download template</a>.</p>
                    <p class="text-muted">
                        Semua mata kuliah dari file ini akan otomatis dimasukkan ke dalam kurikulum <strong>{{ $curriculum->name }}</strong>.
                    </p>
                    {{-- <a href="/templates/template_mk.xlsx">Download Template</a> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- Tampilkan error validasi dari import --}}
    @if (session('import_errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan saat mengimpor data:</strong>
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
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
