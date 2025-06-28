<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Unggah Bukti Pembayaran</h3>
    </div>
    <div class="card-body">
        @if ($invoice->status == 'unpaid')
            <form wire:submit.prevent="uploadProof">
                <div class="form-group">
                    <label for="proof_of_payment">Pilih File Bukti Transfer (JPG, PNG)</label>
                    <input type="file" class="form-control" wire:model="proof_of_payment" id="proof_of_payment" required>
                    @error('proof_of_payment') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div wire:loading wire:target="proof_of_payment">Uploading...</div>
                <button type="submit" class="btn btn-success mt-3">
                    <div wire:loading.remove wire:target="uploadProof">Kirim Bukti Pembayaran</div>
                    <div wire:loading wire:target="uploadProof">Mengunggah...</div>
                </button>
            </form>
        @elseif($invoice->status == 'pending_verification')
            <div class="alert alert-info">
                Bukti pembayaran Anda sedang menunggu verifikasi oleh tim kami. Terima kasih.
            </div>
            <a href="{{ Storage::url($invoice->proof_of_payment) }}" target="_blank">Lihat bukti yang diunggah</a>
        @elseif($invoice->status == 'paid')
             <div class="alert alert-success">
                Pembayaran Anda telah diverifikasi. Selamat! Anda selangkah lagi menjadi mahasiswa resmi.
            </div>
        @endif
    </div>
</div>