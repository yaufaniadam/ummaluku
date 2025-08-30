<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $feeComponent ? 'Edit' : 'Tambah' }} Komponen Biaya</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Komponen Biaya</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Contoh: SPP Tetap Ganjil, Biaya per SKS">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="type">Tipe Komponen</label>
                    <select wire:model="type" id="type" class="form-control @error('type') is-invalid @enderror">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="fixed">Biaya Tetap (Fixed)</option>
                        <option value="per_sks">Biaya per SKS</option>
                        <option value="per_course">Biaya per Mata Kuliah (Praktikum, dll)</option>
                    </select>
                    @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.fee-components.index') }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>