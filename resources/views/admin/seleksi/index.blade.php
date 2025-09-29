@extends('adminlte::page')

@section('title', 'Proses Seleksi Pendaftar')

@section('content_header')
    <h1>Proses Seleksi Pendaftar</h1>
@stop

@section('content')
    {{-- <p>Halaman ini berisi daftar calon mahasiswa yang telah lolos verifikasi dokumen dan siap untuk proses seleksi akhir.</p> --}}
    
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">Pendaftar Siap Seleksi</h3>
        </div> --}}
        <div class="card-body">
            <table class="table table-bordered table-hover" id="selection-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Registrasi</th>
                        <th>Nama Pendaftar</th>
                        <th>Pilihan Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Biarkan kosong, akan diisi oleh JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>
@stop

@push('js')
<script>
    // Inisialisasi DataTables
    $(function() {
        $('#selection-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.pmb.seleksi.data') }}', // URL sumber data
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'registration_number', name: 'registration_number' },
                // Kita ambil nama dari relasi
                { data: 'prospective.user.name', name: 'prospective.user.name' },
                { data: 'program_choices', name: 'programChoices.program.name_id', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        // TAMBAHKAN BLOK IF DI BAWAH INI
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                type: 'success',
                confirmButtonText: 'Oke'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                type: 'danger',
                confirmButtonText: 'Oke'
            });
        @endif
    });
</script>
@endpush