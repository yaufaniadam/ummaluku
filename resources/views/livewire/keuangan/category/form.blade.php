<div>
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Kategori</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="type">Tipe</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" wire:model="type">
                                <option value="income">Pemasukan (Income)</option>
                                <option value="expense">Pengeluaran (Expense)</option>
                            </select>
                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"></textarea>
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-category-modal', () => {
            $('#categoryModal').modal('show');
        });
        $wire.on('hide-category-modal', () => {
            $('#categoryModal').modal('hide');
        });
    </script>
    @endscript
</div>
