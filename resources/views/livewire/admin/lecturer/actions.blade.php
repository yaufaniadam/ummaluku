<div>
    @if ($confirmingDelete)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" wire:click="$set('confirmingDelete', false)">&times;</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data dosen: <strong>{{ $lecturer->full_name_with_degree ?? '' }}</strong>? Data akan dipindahkan ke arsip.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($confirmingToggleStatus)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Ganti Status</h5>
                    <button type="button" class="close" wire:click="$set('confirmingToggleStatus', false)">&times;</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mengubah status dosen: <strong>{{ $lecturer->full_name_with_degree ?? '' }}</strong>
                    menjadi <strong>{{ ($lecturer->status ?? '') === 'active' ? 'Non-Aktif' : 'Aktif' }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingToggleStatus', false)">Batal</button>
                    <button type="button" class="btn btn-success" wire:click="toggleStatus">Ya, Ubah</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>