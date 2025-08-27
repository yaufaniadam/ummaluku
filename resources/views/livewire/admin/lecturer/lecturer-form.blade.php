<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                {{-- Menjadi seperti ini --}}
                <h3 class="card-title">
                    @if ($lecturer)
                        Formulir Edit Dosen
                    @else
                        Formulir Tambah Dosen
                    @endif
                </h3>
            </div>
            <div class="card-body">
                {{-- Baris NIDN & Nama Lengkap --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nidn">NIDN</label>
                            <input type="text" wire:model="nidn"
                                class="form-control @error('nidn') is-invalid @enderror" id="nidn"
                                placeholder="Masukkan NIDN">
                            @error('nidn')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullName">Nama Lengkap (dengan gelar)</label>
                            <input type="text" wire:model="fullName"
                                class="form-control @error('fullName') is-invalid @enderror" id="fullName"
                                placeholder="Contoh: Dr. Budi Santoso, M.Kom.">
                            @error('fullName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Baris Email & Program Studi --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" wire:model="email"
                                class="form-control @error('email') is-invalid @enderror" id="email"
                                placeholder="Masukkan Email Aktif">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="program_id">Program Studi</label>
                            <select wire:model="program_id"
                                class="form-control @error('program_id') is-invalid @enderror" id="program_id">
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                                @endforeach
                            </select>
                            @error('program_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Baris Password --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" wire:model="password"
                                class="form-control @error('password') is-invalid @enderror" id="password"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" wire:model="password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="Ulangi password">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <div wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">Menyimpan...</span>
                    </div>
                    Simpan
                </button>
                <a href="{{ route('admin.lecturers.index') }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>
