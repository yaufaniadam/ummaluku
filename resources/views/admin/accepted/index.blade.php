@extends('adminlte::page')

@section('title', 'Mahasiswa Diterima')

@section('content_header')
    <h1>Daftar Calon Mahasiswa Diterima</h1>
@stop

@section('content')
    {{-- <p>Halaman ini berisi daftar calon mahasiswa yang sudah lolos seleksi dan siap untuk melakukan registrasi ulang.</p> --}}

    <div class="card mb-4">
        {{-- <div class="card-header"><h3 class="card-title">Filter Data</h3></div> --}}
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
                        <select id="payment-status-filter" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach ($payment_statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop

@push('js')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    <script>
        $(function() {
            const table = window.LaravelDataTables['acceptedstudents-table'];
            $('#program-filter, #category-filter, #batch-filter, #payment-status-filter').on('change', function() {
                table.draw();
            });
        });
    </script>
@endpush
