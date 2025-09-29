<div>
    @if ($showDetailModal && $academicEvent)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Kegiatan</h5>
                    <button type="button" class="close" wire:click="closeModals">&times;</button>
                </div>
                <div class="modal-body">
                    <h6>{{ $academicEvent->name }}</h6>
                    <p><strong>Tanggal:</strong> 
                        {{ $academicEvent->start_date->isoFormat('D MMMM YYYY') }}
                        @if($academicEvent->end_date && $academicEvent->start_date != $academicEvent->end_date)
                            - {{ $academicEvent->end_date->isoFormat('D MMMM YYYY') }}
                        @endif
                    </p>
                    <p><strong>Deskripsi:</strong><br>{{ $academicEvent->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>
                <div class="modal-footer">
                    {{-- Tombol Hapus sekarang memanggil method Livewire --}}
                    <button type="button" class="btn btn-secondary" wire:click="closeModals">Tutup</button>
                    <a href="{{ route('admin.akademik.academic-events.edit', $academicEvent->id) }}" class="btn btn-primary" wire:navigate>Edit Event</a>
                    <button type="button" class="btn btn-danger" wire:click="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($confirmingDelete && $academicEvent)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" wire:click="closeModals">&times;</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus event: <strong>{{ $academicEvent->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModals">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>