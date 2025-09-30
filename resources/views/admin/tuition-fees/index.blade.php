@extends('adminlte::page')

@section('title', 'Pengaturan Biaya Kuliah')

@section('content_header')
    <h1>Pengaturan Biaya Kuliah</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- TAMBAHKAN BLOK INI UNTUK MENAMPILKAN PESAN ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div x-data @tuition-fee-updated.window="window.LaravelDataTables['tuitionfee-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Biaya Kuliah per Prodi & Angkatan</h3>
                <div class="card-tools">

                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#duplicateModal">
                        <i class="fas fa-copy"></i> Salin Biaya Angkatan
                    </button>

                    <a href="{{ route('admin.keuangan.tuition-fees.create') }}" class="btn btn-primary btn-sm"
                        wire:navigate>Tambah Biaya Baru</a>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>


    {{-- Modal untuk Duplikasi Biaya --}}
    <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateModalLabel">Salin Struktur Biaya Angkatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.keuangan.tuition-fees.duplicate') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Fitur ini akan menyalin semua pengaturan biaya dari satu angkatan ke angkatan lainnya. Jika di
                            angkatan tujuan sudah ada data, data tersebut akan ditimpa.</p>
                        <hr>
                        <div class="form-group">
                            <label for="source_year">Salin Biaya Dari Tahun Angkatan:</label>
                            <input type="number" name="source_year" class="form-control" placeholder="Contoh: 2024"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="target_year">Untuk Tahun Angkatan Baru:</label>
                            <input type="number" name="target_year" class="form-control" placeholder="Contoh: 2025"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Mulai Salin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
