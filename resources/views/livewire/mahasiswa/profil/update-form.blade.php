<div>
    <form wire:submit="updateProfile">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Lengkapi Biodata Anda</h3>
            </div>
            <div class="card-body">
                {{-- Data Diri --}}
                <h6>Data Diri</h6>
                <hr>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nama Lengkap (sesuai ijazah)</label><input type="text" wire:model="full_name"
                            class="form-control @error('full_name') is-invalid @enderror">
                        @error('full_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>No. HP Aktif</label><input type="text"
                            wire:model.live="phone" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Contoh: 081234567890">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>NIK</label><input type="text" wire:model.live="id_number"
                            class="form-control @error('id_number') is-invalid @enderror">
                        @error('id_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 form-group"><label>Tempat Lahir</label><input type="text"
                            wire:model="birth_place" class="form-control @error('birth_place') is-invalid @enderror">
                        @error('birth_place')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3 form-group"><label>Tanggal Lahir</label><input type="date"
                            wire:model="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                            max="{{ date('Y-m-d') }}">
                        @error('birth_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group"><label>Agama</label><select wire:model="religion_id"
                            class="form-control @error('religion_id') is-invalid @enderror">
                            <option value="">-- Pilih Agama --</option>
                            @foreach ($religions as $religion)
                                <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                            @endforeach
                        </select>
                        @error('religion_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>Kewarganegaraan</label><select wire:model="citizenship"
                            class="form-control @error('citizenship') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="WNI">WNI</option>
                            <option value="WNA">WNA</option>
                        </select>
                        @error('citizenship')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>Asal Sekolah</label>
                        <div class="input-group"><input type="text"
                                class="form-control @error('high_school_id') is-invalid @enderror"
                                placeholder="Pilih sekolah..." readonly wire:model="nama_sekolah">
                            <div class="input-group-append"><button type="button" class="btn btn-secondary"
                                    wire:click="$set('showModal', true)"><i class="fas fa-search"></i> Cari
                                    Sekolah</button></div>
                        </div>
                        @error('high_school_id')
                            <span class="text-danger small mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group"><label>Jurusan SMA/Sederajat</label><select
                            class="form-control @error('high_school_major_id') is-invalid @enderror"
                            wire:model="high_school_major_id">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach ($highSchoolMajors as $major)
                                <option value="{{ $major->id }}">{{ $major->name }}</option>
                            @endforeach
                        </select>
                        @error('high_school_major_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group"><label>Penerima KPS?</label>
                        <select wire:model="is_kps_recipient"
                            class="form-control">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                </div>

                {{-- Alamat --}}
                <h6 class="mt-4">Alamat Sesuai KTP</h6>
                <hr>
                <div class="row">
                    <div class="col-12 form-group"><label>Alamat Lengkap (Jalan, RT/RW)</label><input type="text"
                            wire:model="address" class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>Provinsi</label><select
                            class="form-control @error('province_code') is-invalid @enderror"
                            wire:model.live="province_code">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('province_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>Kabupaten/Kota</label>
                        <div wire:loading wire:target="province_code" class="text-muted small">Mencari...</div>
                        <select class="form-control @error('city_code') is-invalid @enderror"
                            wire:model.live="city_code">
                            <option value="">-- Pilih --</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->code }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('city_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>Kecamatan</label>
                        <div wire:loading wire:target="city_code" class="text-muted small">Mencari...</div><select
                            class="form-control @error('district_code') is-invalid @enderror"
                            wire:model.live="district_code">
                            <option value="">-- Pilih --</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->code }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                        @error('district_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>Desa/Kelurahan</label>
                        <div wire:loading wire:target="district_code" class="text-muted small">Mencari...</div>
                        <select class="form-control @error('village_code') is-invalid @enderror"
                            wire:model="village_code">
                            <option value="">-- Pilih --</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->code }}">{{ $village->name }}</option>
                            @endforeach
                        </select>
                        @error('village_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Data Orang Tua --}}
                <h6 class="mt-4">Data Orang Tua / Wali</h6>
                <hr>
                <div class="row">
                    <div class="col-md-4 form-group"><label>Nama Ayah</label><input type="text"
                            wire:model="father_name" class="form-control @error('father_name') is-invalid @enderror">
                        @error('father_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group"><label>Pekerjaan Ayah</label><input type="text"
                            wire:model="father_occupation"
                            class="form-control @error('father_occupation') is-invalid @enderror">
                        @error('father_occupation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group"><label>Penghasilan Ayah</label><input type="number"
                            wire:model="father_income"
                            class="form-control @error('father_income') is-invalid @enderror">
                        @error('father_income')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group"><label>Nama Ibu Kandung</label><input type="text"
                            wire:model="mother_name" class="form-control @error('mother_name') is-invalid @enderror">
                        @error('mother_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group"><label>Pekerjaan Ibu</label><input type="text"
                            wire:model="mother_occupation"
                            class="form-control @error('mother_occupation') is-invalid @enderror">
                        @error('mother_occupation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group"><label>Penghasilan Ibu</label><input type="number"
                            wire:model="mother_income"
                            class="form-control @error('mother_income') is-invalid @enderror">
                        @error('mother_income')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group"><label>No. HP Orang Tua / Wali</label><input type="text"
                            wire:model="parent_phone"
                            class="form-control @error('parent_phone') is-invalid @enderror">
                        @error('parent_phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <span wire:loading wire:target="updateProfile" class="spinner-border spinner-border-sm"
                        role="status" aria-hidden="true"></span>
                    Update Profil
                </button>
            </div>
        </div>
    </form>

    {{-- Modal Pencarian Sekolah --}}
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cari dan Pilih Sekolah</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <livewire:pendaftar.modal-cari-sekolah¸ />
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
