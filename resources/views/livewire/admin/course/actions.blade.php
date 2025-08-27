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
                    Apakah Anda yakin ingin menghapus mata kuliah: <strong>{{ $course->name ?? '' }}</strong>? Data akan diarsipkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>