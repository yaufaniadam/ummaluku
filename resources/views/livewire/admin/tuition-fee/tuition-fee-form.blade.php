<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $tuitionFee ? 'Edit' : 'Tambah' }} Biaya Kuliah</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="program_id">Program Studi</label>
                    <select wire:model="program_id" id="program_id" class="form-control @error('program_id') is-invalid @enderror">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                        @endforeach
                    </select>
                    @error('program_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="entry_year">Tahun Angkatan</label>
                    <input type="number" wire:model.lazy="entry_year" class="form-control @error('entry_year') is-invalid @enderror" id="entry_year" placeholder="Contoh: 2025">
                    @error('entry_year') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="fee_component_id">Komponen Biaya</label>
                    <select wire:model="fee_component_id" id="fee_component_id" class="form-control @error('fee_component_id') is-invalid @enderror">
                        <option value="">-- Pilih Komponen Biaya --</option>
                        @foreach ($feeComponents as $component)
                            <option value="{{ $component->id }}">{{ $component->name }} ({{$component->type}})</option>
                        @endforeach
                    </select>
                    @error('fee_component_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="amount">Jumlah Biaya (Rp)</label>
                    <input type="number" wire:model="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Contoh: 5000000">
                    @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.tuition-fees.index') }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>