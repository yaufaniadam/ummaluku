<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $academicEvent ? 'Edit' : 'Tambah' }} Event Akademik</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="academic_year_id">Untuk Semester</label>
                    <select wire:model="academic_year_id" id="academic_year_id"
                        class="form-control @error('academic_year_id') is-invalid @enderror">
                        <option value="">-- Pilih Semester --</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Nama Kegiatan</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror"
                        id="name" placeholder="Contoh: Periode Ujian Tengah Semester (UTS)">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Warna Event</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($colorPalette as $hex => $name)
                            <div class="form-check mr-3">
                                <input class="form-check-input" type="radio" wire:model="color"
                                    id="color-{{ $hex }}" value="{{ $hex }}">
                                <label class="form-check-label" for="color-{{ $hex }}">
                                    <span class="px-2 py-1 rounded"
                                        style="background-color: {{ $hex }}; border: 1px solid #ddd;">&nbsp;</span>
                                    {{ $name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('color')
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" wire:model="start_date"
                                class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai (Opsional)</label>
                            <input type="date" wire:model="end_date"
                                class="form-control @error('end_date') is-invalid @enderror" id="end_date">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi (Opsional)</label>
                    <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description"
                        rows="3"></textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.academic-events.index') }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>
