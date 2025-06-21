<div>
    {{-- Mulai dari sini, tidak perlu @extends atau @section --}}
    <div class="container py-4">
        <h2 class="mb-4">Dashboard Pendaftaran: {{ $application->prospective->user->name }}</h2>

        <div class="card card-body mb-4">

            <h3 class="card-title mb-4">Lengkapi Biodata Anda</h3>
            <form wire:submit.prevent="saveBiodata">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                            wire:model.live="nisn" placeholder="Masukkan NISN Anda...">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                            wire:model.live="id_number" placeholder="Masukkan 16 digit NIK...">
                        @error('id_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                     <div class="col-md-6 mb-3">
                        <label class="form-label required">Agama</label>
                        <select class="form-select @error('religion_id') is-invalid @enderror"
                            wire:model.live="religion_id">
                            <option value="">-- Pilih Agama --</option>
                            @foreach ($religions as $religion)
                                <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                            @endforeach
                        </select>
                        @error('religion_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Asal Sekolah</label>
                        <select class="form-select @error('high_school_id') is-invalid @enderror"
                            wire:model.live="high_school_id">
                            <option value="">-- Pilih Asal Sekolah --</option>
                            @foreach ($highSchools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('high_school_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> 



                </div>
                 <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label required">Alamat Lengkap (Sesuai KTP)</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                            wire:model.live="address" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kel. ...">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                
                     <div class="col-md-6 mb-3">
                        <label class="form-label required">Provinsi</label>
                    
                        <select class="form-select  @error('province_code') is-invalid @enderror" wire:model.live="province_code">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}">{{ $province->name }}</option>                             
                            @endforeach
                        </select>
                        @error('province_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Kabupaten/Kota</label>
                   
                        <div wire:loading wire:target="province_code" class="text-muted">Mencari kabupaten...</div>
                        <select class="form-select  @error('city_code') is-invalid @enderror" wire:model.live="city_code" ...>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->code }}">{{ $city->name }}</option>                     
                            @endforeach
                        </select>
                        @error('city_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
{{-- 
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Kecamatan</label>
                        <div wire:loading wire:target="city_code" class="text-muted">Mencari kecamatan...</div>
                        <select class="form-select ..." wire:model.live="district_code" ...>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->code }}">{{ $district->name }}</option>                 
                            @endforeach
                        </select>
                        @error('district_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Desa/Kelurahan</label>
                        <div wire:loading wire:target="district_code" class="text-muted">Mencari desa...</div>
                        <select class="form-select ..." wire:model.live="village_code" ...>
                            <option value="">-- Pilih Desa/Kelurahan --</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->code }}">{{ $village->name }}</option>                     
                            @endforeach
                        </select>
                        @error('village_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>--}}
                </div> 

                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary ms-auto">
                        <div wire:loading.remove wire:target="saveBiodata">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2">
                                </path>
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M14 4l0 4l-6 0l0 -4"></path>
                            </svg>
                            Simpan Perubahan Biodata
                        </div>
                        <div wire:loading wire:target="saveBiodata">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Menyimpan...
                        </div>
                    </button>
                </div>

            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Dokumen Persyaratan</h3>
            </div>
            <div class="card-body p-0">
                {{-- Tabel upload dokumen non-livewire Anda di sini --}}
            </div>
        </div>
    </div>

    @push('js')
        {{-- Listener untuk SweetAlert tetap kita perlukan --}}
        <script>
            // Mendengarkan event 'swal-success' yang dikirim dari komponen Livewire
            document.addEventListener('swal-success', event => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Biodata Anda berhasil diperbarui!',
                    icon: 'success',
                    confirmButtonText: 'Oke'
                });
            });
        </script>
    @endpush
</div>
