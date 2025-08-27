<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    @if ($curriculum)
                        Formulir Edit Kurikulum
                    @else
                        Formulir Tambah Kurikulum
                    @endif
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Kurikulum</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Contoh: Kurikulum Merdeka 2024">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="program_id">Program Studi</label>
                    <select wire:model="program_id" class="form-control @error('program_id') is-invalid @enderror" id="program_id">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                        @endforeach
                    </select>
                    @error('program_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="start_year">Tahun Mulai Berlaku</label>
                    <input type="number" wire:model="start_year" class="form-control @error('start_year') is-invalid @enderror" id="start_year" placeholder="Contoh: 2024">
                    @error('start_year') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="is_active">Status</label>
                    <div>
                        <input type="checkbox" wire:model="is_active" id="is_active">
                        <label for="is_active">Aktif</label>
                    </div>
                    @error('is_active') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.curriculums.index') }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>