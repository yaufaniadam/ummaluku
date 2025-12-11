<div>
    @section('title', $course ? 'Edit Matakuliah' : 'Tambah Matakuliah')
    @section('content_header')
        <h1>{{ $course ? 'Edit Matakuliah' : 'Tambah Matakuliah' }}</h1>
    @endsection

    <div class="card">
        <form wire:submit.prevent="save">
            <div class="card-body">
                <div class="form-group">
                    <label>Kurikulum</label>
                    <select wire:model="curriculum_id" class="form-control @error('curriculum_id') is-invalid @enderror">
                        <option value="">-- Pilih Kurikulum --</option>
                        @foreach($curriculums as $curr)
                            <option value="{{ $curr->id }}">{{ $curr->name }} ({{ $curr->start_year }})</option>
                        @endforeach
                    </select>
                    @error('curriculum_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Kode Matakuliah</label>
                    <input type="text" wire:model="code" class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: IF101">
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Nama Matakuliah</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Algoritma dan Pemrograman">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>SKS</label>
                    <input type="number" wire:model="sks" class="form-control @error('sks') is-invalid @enderror" min="1" max="6">
                    @error('sks') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Rekomendasi Semester</label>
                    <input type="number" wire:model="semester_recommendation" class="form-control @error('semester_recommendation') is-invalid @enderror" min="1" max="8">
                    @error('semester_recommendation') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Jenis</label>
                    <select wire:model="type" class="form-control @error('type') is-invalid @enderror">
                        <option value="Wajib">Wajib</option>
                        <option value="Pilihan">Pilihan</option>
                        <option value="Wajib Peminatan">Wajib Peminatan</option>
                    </select>
                    @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('prodi.course.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
</div>
