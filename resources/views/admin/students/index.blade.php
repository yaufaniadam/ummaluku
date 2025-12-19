@extends('adminlte::page')

@section('title', 'Manajemen Mahasiswa')

@section('content_header')
    <h1>Manajemen Mahasiswa</h1>
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

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter Data Mahasiswa</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-generate-krs-massal">
                    <i class="fas fa-magic mr-1"></i> Generate KRS Paket Massal
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select id="filter_program" class="form-control">
                            <option value="">Semua Program Studi</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Angkatan</label>
                        <select id="filter_angkatan" class="form-control">
                            <option value="">Semua Angkatan</option>
                             @foreach ($entryYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select id="filter_status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="on_leave">Cuti</option>
                            <option value="graduated">Lulus</option>
                            <option value="dropped_out">Drop Out</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data @student-updated.window="window.LaravelDataTables['student-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Mahasiswa</h3>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <livewire:admin.student.generate-krs-massal />

    <script type="module">
        $(function() {
            const table = window.LaravelDataTables['student-table'];

            // Fungsi untuk menerapkan semua filter sekaligus
            function applyFilters() {
                const programId = $('#filter_program').val();
                const angkatan = $('#filter_angkatan').val();
                const status = $('#filter_status').val();

                // Bangun URL baru dengan semua parameter filter
                const newUrl = '{{ route("admin.akademik.students.data") }}?' + 
                               'program_id=' + programId +
                               '&entry_year=' + angkatan +
                               '&status=' + status;
                
                // Set URL baru dan load ulang tabel
                table.ajax.url(newUrl).load();
            }

            // Tambahkan event listener ke setiap dropdown
            $('#filter_program, #filter_angkatan, #filter_status').on('change', function() {
                applyFilters();
            });
        });
    </script>
@endpush
