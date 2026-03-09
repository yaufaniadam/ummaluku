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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employment_status_id">Status Kepegawaian</label>
                            <select wire:model="employment_status_id"
                                class="form-control @error('employment_status_id') is-invalid @enderror" id="employment_status_id">
                                <option value="">-- Pilih Status --</option>
                                @foreach ($employmentStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('employment_status_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo">Foto Profil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo" wire:model="photo">
                                    <label class="custom-file-label" for="photo">Pilih file</label>
                                </div>
                            </div>
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @if ($user && $user->profile_photo_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto Profil" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select wire:model="gender" class="form-control @error('gender') is-invalid @enderror" id="gender">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">No. HP/WA</label>
                            <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" id="phone">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="birth_place">Tempat Lahir</label>
                            <input type="text" wire:model="birth_place" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place">
                            @error('birth_place')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="birth_date">Tanggal Lahir</label>
                            <input type="date" wire:model="birth_date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date">
                            @error('birth_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_name">Nama Bank</label>
                            <input type="text" wire:model="bank_name" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" placeholder="Contoh: Bank Mandiri">
                            @error('bank_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_number">Nomor Rekening</label>
                            <input type="text" wire:model="account_number" class="form-control @error('account_number') is-invalid @enderror" id="account_number">
                            @error('account_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3"></textarea>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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
                <a href="{{ route('admin.sdm.lecturers.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
