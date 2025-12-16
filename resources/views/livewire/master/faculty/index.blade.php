<div>
    @section('title', 'Master Fakultas')
    @section('content_header')
        <h1>Master Fakultas</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Fakultas</h3>
            <div class="card-tools">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari Fakultas...">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama (ID)</th>
                        <th>Nama (EN)</th>
                        <th>Jumlah Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faculties as $index => $faculty)
                        <tr>
                            <td>{{ $faculties->firstItem() + $index }}</td>
                            <td>{{ $faculty->name_id }}</td>
                            <td>{{ $faculty->name_en ?? '-' }}</td>
                            <td>{{ $faculty->programs_count }}</td>
                            <td>
                                <button wire:click="edit({{ $faculty->id }})" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="{{ route('master.faculties.manage-officials', $faculty->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-user-tie"></i> Pejabat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data fakultas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $faculties->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Fakultas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#editModal').modal('hide')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update">
                        <div class="form-group">
                            <label>Nama (Indonesia)</label>
                            <input type="text" wire:model="name_id" class="form-control">
                            @error('name_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama (Inggris)</label>
                            <input type="text" wire:model="name_en" class="form-control">
                            @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#editModal').modal('hide')">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        window.addEventListener('open-modal', event => {
            $('#editModal').modal('show');
        });
        window.addEventListener('close-modal', event => {
            $('#editModal').modal('hide');
        });
        window.addEventListener('alert', event => {
            toastr[event.detail.type](event.detail.message);
        });
    </script>
    @endpush
</div>
