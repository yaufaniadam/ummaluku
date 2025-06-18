<div>
    {{-- Komentar: Karena ini komponen Livewire, semua elemen harus berada di dalam satu div utama. --}}

    <form wire:submit.prevent="save">
        <div class="row row-cards">

            <div class="col-md-12">
                <div class="card card-stacked">
                    <div class="card-header">
                        <h3 class="card-title">1. Data Diri Calon Mahasiswa</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model.defer="name" placeholder="Sesuai Ijazah">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Alamat Email Aktif</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model.defer="email" placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nomor Telepon (WhatsApp)</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    wire:model.defer="phone" placeholder="08...">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Jenis Kelamin</label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                    wire:model.defer="gender">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Tempat Lahir</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror"
                                    wire:model.defer="birth_place">
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                    wire:model.defer="birth_date">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                    wire:model.defer="id_number">
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">NISN (Nomor Induk Siswa Nasional)</label>
                                <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                    wire:model.defer="nisn">
                                @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Alamat Lengkap</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" wire:model.defer="address" rows="3"></textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-stacked">
                    <div class="card-header">
                        <h3 class="card-title">2. Data Akademik & Pilihan Program Studi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Agama</label>
                                <select class="form-select @error('religion_id') is-invalid @enderror"
                                    wire:model.defer="religion_id">
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach ($religions as $religion)
                                        <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                    @endforeach
                                </select>
                                @error('religion_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Asal Sekolah</label>
                                <select class="form-select @error('high_school_id') is-invalid @enderror"
                                    wire:model.defer="high_school_id">
                                    <option value="">-- Pilih Asal Sekolah --</option>
                                    @foreach ($highSchools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                @error('high_school_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Pilihan Program Studi 1</label>
                                <select class="form-select @error('program_choice_1') is-invalid @enderror"
                                    wire:model.defer="program_choice_1">
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($programs as $facultyName => $facultyPrograms)
                                        <optgroup label="{{ $facultyName }}">
                                            @foreach ($facultyPrograms as $program)
                                                <option value="{{ $program->id }}">{{ $program->name_id }}
                                                    ({{ $program->degree }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('program_choice_1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Lakukan hal yang sama untuk Pilihan Program Studi 2 --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilihan Program Studi 2 (Opsional)</label>
                                <select class="form-select @error('program_choice_2') is-invalid @enderror"
                                    wire:model.defer="program_choice_2">
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($programs as $facultyName => $facultyPrograms)
                                        <optgroup label="{{ $facultyName }}">
                                            @foreach ($facultyPrograms as $program)
                                                <option value="{{ $program->id }}">{{ $program->name_id }}
                                                    ({{ $program->degree }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('program_choice_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-stacked">
                    <div class="card-header">
                        <h3 class="card-title">3. Data Orang Tua / Wali</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nama Ayah</label>
                                <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                    wire:model.defer="father_name">
                                @error('father_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Pekerjaan Ayah</label>
                                <input type="text"
                                    class="form-control @error('father_occupation') is-invalid @enderror"
                                    wire:model.defer="father_occupation">
                                @error('father_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nama Ibu</label>
                                <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                    wire:model.defer="mother_name">
                                @error('mother_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Pekerjaan Ibu</label>
                                <input type="text"
                                    class="form-control @error('mother_occupation') is-invalid @enderror"
                                    wire:model.defer="mother_occupation">
                                @error('mother_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">No. Telepon Orang Tua/Wali</label>
                                <input type="text"
                                    class="form-control @error('parent_phone') is-invalid @enderror"
                                    wire:model.defer="parent_phone">
                                @error('parent_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Wali (Jika ada)</label>
                                <input type="text"
                                    class="form-control @error('guardian_name') is-invalid @enderror"
                                    wire:model.defer="guardian_name">
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerjaan Wali</label>
                                <input type="text"
                                    class="form-control @error('guardian_occupation') is-invalid @enderror"
                                    wire:model.defer="guardian_occupation">
                                @error('guardian_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">No. Telepon Wali</label>
                                <input type="text"
                                    class="form-control @error('guardian_phone') is-invalid @enderror"
                                    wire:model.defer="guardian_phone">
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tombol Submit --}}
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <div wire:loading.remove wire:target="save">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M14 4l0 4l-6 0l0 -4"></path>
                    </svg>
                    Daftar Sekarang
                </div>
                <div wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Menyimpan Data...
                </div>
            </button>
        </div>
    </form>
</div>
