@extends('adminlte::page')

@section('title', 'Data Penerimaan Mahasiswa')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Data Penerimaan Mahasiswa Baru</h1>
        <a href="{{ route('executive.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    {{-- Filter Card --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select id="program-filter" class="form-control">
                            <option value="">Semua Prodi</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jalur Pendaftaran</label>
                        <select id="category-filter" class="form-control">
                            <option value="">Semua Jalur</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Gelombang</label>
                        <select id="batch-filter" class="form-control">
                            <option value="">Semua Gelombang</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status Pembayaran Registrasi</label>
                        <select id="status-filter" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>Daftar Pendaftar</h3>
        </div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop

@section('js')
    {!! $dataTable->scripts() !!}
    <script>
        $(function() {
            const table = window.LaravelDataTables['executive-pmb-table'];
            $('#program-filter, #category-filter, #batch-filter, #status-filter').on('change', function() {
                table.draw();
            });
        });
    </script>
@stop
