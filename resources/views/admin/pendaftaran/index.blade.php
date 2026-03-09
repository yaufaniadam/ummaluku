@extends('adminlte::page')

@section('title', 'Data Pendaftar')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Data Pendaftar</h1>
        <a href="{{ route('admin.pmb.pendaftaran.export') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Data Lengkap
        </a>
    </div>
@stop


@section('content')
    <div class="card mb-4">
        {{-- <div class="card-header">
            <h3 class="card-title">Filter Data</h3>
        </div> --}}
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category-filter">Jalur Pendaftaran</label>
                        <select id="category-filter" class="form-control">
                            <option value="">Semua Jalur</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="batch-filter">Gelombang</label>
                        <select id="batch-filter" class="form-control">
                            <option value="">Semua Gelombang</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }} - {{ $batch->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status-filter">Status</label>
                        <select id="status-filter" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{-- Tambahkan 'selected' jika status ini adalah default --}}
                                    {{ $defaultStatus == $status ? 'selected' : '' }}>
                                    {{ Str::title(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{-- Yajra akan otomatis membuat tabel di sini --}}
            {!! $dataTable->table() !!}
        </div>
    </div>

    {{-- @livewire('admin.pendaftaran.index') --}}
@endsection

@push('js')
    {{-- Baris ini sudah benar, ia akan membuat skrip inisialisasi DataTables --}}
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

    <script>
        // Gunakan jQuery yang sudah dimuat oleh AdminLTE
        $(function() {
            // Kita tunggu sampai tabel benar-benar siap
            $('#applications-table').on('draw.dt', function() {
                // Ambil instance tabel setelah digambar
                const table = window.LaravelDataTables['applications-table'];

                // Tambahkan event listener untuk setiap dropdown filter
                $('#category-filter, #batch-filter, #status-filter').on('change', function() {
                    // Gambar ulang (refresh) tabel setiap kali ada perubahan
                    table.draw();
                });
            });
        });
    </script>
@endpush
