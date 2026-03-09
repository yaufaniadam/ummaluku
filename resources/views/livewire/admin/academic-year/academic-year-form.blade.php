<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $academicYear ? 'Edit' : 'Tambah' }} Tahun Ajaran</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Tahun Ajaran</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Contoh: Semester Ganjil 2025/2026">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year_code">Kode</label>
                            <input type="text" wire:model="year_code" class="form-control @error('year_code') is-invalid @enderror" id="year_code" placeholder="Contoh: 20251 (Tahun + 1 untuk Ganjil / 2 untuk Genap)">
                            @error('year_code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="semester_type">Jenis Semester</label>
                            <select wire:model="semester_type" class="form-control @error('semester_type') is-invalid @enderror" id="semester_type">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                            @error('semester_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <p class="font-weight-bold">Periode Perkuliahan</p>
                <div class="row">
                    <div class="col-md-6"><div class="form-group">
                        <label for="start_date">Tanggal Mulai Kuliah</label>
                        <input type="date" wire:model="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                        @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div></div>
                    <div class="col-md-6"><div class="form-group">
                        <label for="end_date">Tanggal Selesai Kuliah</label>
                        <input type="date" wire:model="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
                        @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div></div>
                </div>
                <hr>
                <p class="font-weight-bold">Periode Pengisian KRS</p>
                <div class="row">
                    <div class="col-md-6"><div class="form-group">
                        <label for="krs_start_date">Tanggal Mulai KRS</label>
                        <input type="date" wire:model="krs_start_date" class="form-control @error('krs_start_date') is-invalid @enderror" id="krs_start_date">
                        @error('krs_start_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div></div>
                    <div class="col-md-6"><div class="form-group">
                        <label for="krs_end_date">Tanggal Selesai KRS</label>
                        <input type="date" wire:model="krs_end_date" class="form-control @error('krs_end_date') is-invalid @enderror" id="krs_end_date">
                        @error('krs_end_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div></div>
                </div>
                <hr>
                <div class="form-group">
                    <input type="checkbox" wire:model="is_active" id="is_active">
                    <label for="is_active">Jadikan Semester Aktif (untuk pengisian KRS, dll)</label>
                    @error('is_active') <br><span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.akademik.academic-years.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>