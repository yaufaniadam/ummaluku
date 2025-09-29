@extends('adminlte::page')
@section('title', 'Manajemen Jalur Pendaftaran')
@section('content_header')
    <h1>Manajemen Jalur Pendaftaran</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Jalur</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pmb.jalur-pendaftaran.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Jalur</th>
                        <th>Grup</th>
                        <th>Status</th>
                        <th>Gelombang Terhubung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td><span class="badge badge-secondary">{{ $category->display_group }}</span></td>
                        <td>{!! $category->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            {{-- Tampilkan nama gelombang yang terhubung --}}
                            @forelse($category->batches as $batch)
                                <span class="badge badge-info">{{ $batch->name }}</span>
                            @empty
                                <span class="badge badge-light">Belum diatur</span>
                            @endforelse
                        </td>
                        <td>
                            <div class="btn-group">
                                {{-- Tombol Edit standar --}}
                                <a href="{{ route('admin.pmb.jalur-pendaftaran.edit', $category) }}" class="btn btn-xs btn-default text-primary">Edit</a>
                                
                                @hasrole('Super Admin')
                                {{-- Tombol Hapus standar --}}
                                <form action="{{ route('admin.pmb.jalur-pendaftaran.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-default text-danger" onclick="return confirm('Anda yakin?')">Hapus</button>
                                </form>
                                @endhasrole

                                {{-- Panggil komponen Livewire untuk mengatur gelombang --}}
                                @livewire('admin.pendaftaran.assign-batches-to-category', ['category' => $category], key($category->id))
                            </div>
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

@push('js')
<script>
    document.addEventListener('livewire:init', () => {
        
        // Listener untuk notifikasi SweetAlert
        Livewire.on('show-alert', (event) => {
            Swal.fire({
                // Akses langsung properti 'type' dan 'message'
                title: event.type === 'success' ? 'Berhasil!' : 'Oops!',
                text: event.message,
                icon: event.type,
            });
        });
        
        // Listener untuk membuka modal
        Livewire.on('open-modal', (event) => {
            // Akses langsung properti 'categoryId'
            const categoryId = event.categoryId;
            $('#assignModal-' + categoryId).modal('show');
        });

        // Listener untuk menutup modal
        Livewire.on('close-modal', (event) => {
            // Akses langsung properti 'categoryId'
            const categoryId = event.categoryId;
            $('#assignModal-' + categoryId).modal('hide');
        });
    });
</script>
@endpush