@extends('adminlte::page')

@section('title', 'Kelola Mata Kuliah')

@section('content_header')
    {{-- Judul dinamis menampilkan nama kurikulum --}}
    <h1 class="mb-1">Kelola Mata Kuliah</h1>

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

    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="filter_program" class="col-sm-2 col-form-label">Filter Berdasarkan:</label>
                <div class="col-sm-4">
                    <select id="filter_program" class="form-control">
                        <option value="">Tampilkan Semua</option>
                        <option value="universitas">Tingkat Universitas</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-sm-6 text-right">
                    <button id="delete-selected" class="btn btn-danger btn-sm">Hapus Terpilih</button>
                </div> --}}
            </div>
        </div>
    </div>

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
                    <a href="{{ route('admin.akademik.courses.create') }}" class="btn btn-primary btn-sm" wire:navigate>
                        Tambah Mata Kuliah Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    {{-- Modal untuk Import Mata Kuliah --}}
    <div class="modal fade" id="importCoursesModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Master Mata Kuliah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- PERBAIKAN: Arahkan form action ke route import yang benar --}}
                <form action="{{ route('admin.akademik.courses.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="import_file">Pilih file Excel (.xlsx, .xls)</label>
                            <input type="file" name="import_file" class="form-control-file" id="import_file" required>
                        </div>
                        <div class="form-group">
                            <label for="program_id">Import Untuk</label>
                            <select name="program_id" id="program_id" class="form-control" required>
                                <option value="">-- Pilih Target --</option>
                                <option value="0">Mata Kuliah Tingkat Universitas (MKWU)</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="mt-3">
                            <strong>Penting:</strong> Pastikan file Excel Anda memiliki kolom header berikut:<br>
                            <code>kode_mk</code>, <code>nama_mata_kuliah</code>, <code>sks</code>, <code>semester</code>,
                            <code>jenis</code>.
                        </p>
                        {{-- PERBAIKAN: Hapus teks yang berhubungan dengan kurikulum spesifik --}}
                        <p class="text-muted">
                            Semua mata kuliah dari file ini akan ditambahkan ke dalam Master Data Mata Kuliah.
                        </p>
                        {{-- <a href="{{ asset('template/template_mk.xlsx') }}">Download Template</a> --}}
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

    <script type="module">
        // Tunggu sampai dokumen siap
        $(function() {
            // Simpan instance datatable
            const table = window.LaravelDataTables['course-table'];

            // Tambahkan event listener ke dropdown
            $('#filter_program').on('change', function() {
                // Ambil nilai yang dipilih
                const programId = $(this).val();
                // Set parameter 'program_id' di URL AJAX datatable, lalu load ulang
                table.ajax.url('{{ route("admin.akademik.courses.data") }}?program_id=' + programId).load();
            });
        });


        
    </script>
@endpush
