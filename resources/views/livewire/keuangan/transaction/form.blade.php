<div>
    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $transactionId ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="transaction_date">Tanggal</label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" id="transaction_date" wire:model="transaction_date">
                            @error('transaction_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" wire:model="category_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="amount">Jumlah (Rp)</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" wire:model="amount">
                            @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Keterangan</label>
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
        $wire.on('show-transaction-modal', () => {
            $('#transactionModal').modal('show');
        });
        $wire.on('hide-transaction-modal', () => {
            $('#transactionModal').modal('hide');
        });
    </script>
    @endscript
</div>
