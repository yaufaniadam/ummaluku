<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    @if ($course)
                        Formulir Edit Mata Kuliah
                    @else
                        Formulir Tambah Mata Kuliah
                    @endif
                </h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Kode Mata Kuliah</label>
                            <input type="text" wire:model="code"
                                class="form-control @error('code') is-invalid @enderror" id="code"
                                placeholder="Contoh: INF-101">
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Mata Kuliah</label>
                            <input type="text" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror" id="name"
                                placeholder="Contoh: Dasar Pemrograman">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sks">SKS</label>
                            <input type="number" wire:model="sks"
                                class="form-control @error('sks') is-invalid @enderror" id="sks"
                                placeholder="Contoh: 3">
                            @error('sks')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="semester_recommendation">Rekomendasi Semester</label>
                            <select wire:model="semester_recommendation"
                                class="form-control @error('semester_recommendation') is-invalid @enderror"
                                id="semester_recommendation">
                                <option value="">-- Pilih Semester --</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('semester_recommendation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">Jenis</label>
                            <select wire:model="type" class="form-control @error('type') is-invalid @enderror"
                                id="type">
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                                <option value="Wajib Peminatan">Wajib Peminatan</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.curriculums.courses.index', $curriculum->id) }}" class="btn btn-secondary"
                    wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>
