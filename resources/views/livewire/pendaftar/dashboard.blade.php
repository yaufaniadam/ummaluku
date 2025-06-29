<div>

    <div class="page-header d-print-none pt-5 pb-5 bg-light">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    {{-- Judul Halaman Dinamis --}}
                    <h2 class="page-title">
                        {{ $application->admissionCategory->name }}
                    </h2>
                    <div class="text-muted mt-1">
                        {{ $application->batch->name }} - Tahun Ajaran {{ $application->batch->year }}
                    </div>

                    </p>
                </div>
                <div class="col-md-6 text-lg-end ">
                    <h5 class="card-title">Selamat Datang, {{ $application->prospective->user->name }}!</h5 >
                    <small class="text-muted">No. {{ $application->registration_number }}</small><br>
                    <span>Status: {{ Str::title(str_replace('_', ' ', $application->status)) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Mulai dari sini, tidak perlu @extends atau @section --}}
    <div class="container py-4">

        <x-stepper current-step="2" step1-text="Pendaftaran Awal" step2-text="Lengkapi Biodata"
            step3-text="Upload Dokumen" step4-text="Selesai" />


        

        {{-- BLOK PENGUMUMAN KELULUSAN --}}
        @if ($application->status == 'accepted')
            @php
                $acceptedProgram = $application->programChoices->where('is_accepted', true)->first()->program;
            @endphp
            <div class="alert alert-success" role="alert">
                <h4 class="alert-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-confetti" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 5h2"></path>
                        <path d="M5 4v2"></path>
                        <path d="M11.5 4l-.5 2"></path>
                        <path d="M18 5h2"></path>
                        <path d="M19 4v2"></path>
                        <path d="M15 9l-1 1"></path>
                        <path d="M18 13l2 -.5"></path>
                        <path d="M18 19h2"></path>
                        <path d="M19 18v2"></path>
                        <path d="M14 16.518l-6.518 -6.518l-4.39 9.58a1.003 1.003 0 0 0 1.329 1.329l9.579 -4.39z"></path>
                    </svg>
                    Selamat! Anda Diterima!
                </h4>
                <div class="text-muted">
                    Anda telah diterima sebagai calon mahasiswa baru di Program Studi
                    <strong>{{ $acceptedProgram->name_id }}</strong>.
                    Silakan <a href="{{ route('pendaftar.registrasi') }}">klik di sini</a> untuk informasi selanjutnya
                    mengenai pembayaran dan registrasi ulang.
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title">Lengkapi Biodata Anda</h6>
            </div>
            <div class="card-body">
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
                            <label class="form-label required">Kewarganegaraan</label>
                            <select class="form-select @error('citizenship') is-invalid @enderror"
                                wire:model.live="citizenship">
                                <option value="">-- Pilih Kewarganegaraan --</option>
                                <option value="WNI">WNI (Warga Negara Indonesia)</option>
                                <option value="WNA">WNA (Warga Negara Asing)</option>
                            </select>
                            @error('citizenship')
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

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jurusan</label>
                            <select class="form-select @error('high_school_major_id') is-invalid @enderror"
                                wire:model.live="high_school_major_id">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach ($highSchoolMajor as $major)
                                    <option value="{{ $major->id }}">{{ $major->name }}</option>
                                @endforeach
                            </select>
                            @error('high_school_major_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Penerima KPS (Kartu Perlindungan Sosial)?</label>
                            <select class="form-select @error('is_kps_recipient') is-invalid @enderror"
                                wire:model.live="is_kps_recipient">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            @error('is_kps_recipient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label required">Alamat Lengkap (Sesuai KTP)</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                wire:model.live="address" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02.">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Provinsi</label>

                            <select class="form-select  @error('province_code') is-invalid @enderror"
                                wire:model.live="province_code">
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

                            <div wire:loading wire:target="province_code" class="text-muted">Mencari kabupaten...
                            </div>
                            <select class="form-select  @error('city_code') is-invalid @enderror"
                                wire:model.live="city_code" ...>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->code }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Kecamatan</label>
                            <div wire:loading wire:target="city_code" class="text-muted">Mencari kecamatan...</div>
                            <select class="form-select @error('district_code') is-invalid @enderror"
                                wire:model.live="district_code" ...>
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
                            <select class="form-select @error('village_code') is-invalid @enderror"
                                wire:model.live="village_code" ...>
                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                @foreach ($villages as $village)
                                    <option value="{{ $village->code }}">{{ $village->name }}</option>
                                @endforeach
                            </select>
                            @error('village_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <h6> Orang Tua</h6>
                        <hr>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Nama Ayah</label>
                            <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                wire:model.defer="father_name">
                            @error('father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Pekerjaan Ayah</label>
                            <input type="text"
                                class="form-control @error('father_occupation') is-invalid @enderror"
                                wire:model.defer="father_occupation">
                            @error('father_occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Penghasilan Ayah (Rp)</label>

                            <input type="text" class="form-control @error('father_income') is-invalid @enderror"
                                wire:model.live="father_income">

                            @error('father_income')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Nama Ibu</label>
                            <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                wire:model.defer="mother_name">
                            @error('mother_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Pekerjaan Ibu</label>
                            <input type="text"
                                class="form-control @error('mother_occupation') is-invalid @enderror"
                                wire:model.defer="mother_occupation">
                            @error('mother_occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Penghasilan Ibu (Rp)</label>

                            <input type="text" class="form-control @error('mother_income') is-invalid @enderror"
                                wire:model.live="mother_income">

                            @error('mother_income')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">No. Telepon Orang Tua/Wali</label>
                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror"
                                wire:model.defer="parent_phone" placeholder="Gunakan nomor yang aktif">
                            @error('parent_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Tinggal Bersama Wali?</label>
                            <select class="form-select @error('with_guardian') is-invalid @enderror"
                                wire:model.live="with_guardian">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            @error('with_guardian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Jika wali dipilih --}}

                    @if ($with_guardian)
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Wali</label>
                                <input type="text"
                                    class="form-control @error('guardian_name') is-invalid @enderror"
                                    wire:model.defer="guardian_name">
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pekerjaan Wali</label>
                                <input type="text"
                                    class="form-control @error('guardian_occupation') is-invalid @enderror"
                                    wire:model.defer="guardian_occupation">
                                @error('guardian_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Penghasilan Wali (Rp)</label>

                                <input type="text"
                                    class="form-control @error('guardian_income') is-invalid @enderror"
                                    wire:model.live="guardian_income">

                                @error('guardian_income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    @endif


                    <div class="d-flex mt-4">
                        <button type="submit" class="btn btn-primary ms-auto">
                            <div wire:loading.remove wire:target="saveBiodata">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2">
                                    </path>
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                    <path d="M14 4l0 4l-6 0l0 -4"></path>
                                </svg>
                                Simpan Perubahan Biodata
                            </div>
                            <div wire:loading wire:target="saveBiodata">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Menyimpan...
                            </div>
                        </button>


                    </div>

                </form>
            </div>
        </div>

        @push('js')
            {{-- Listener untuk SweetAlert tetap kita perlukan --}}
            {{-- <script>
                // Mendengarkan event 'swal-success' yang dikirim dari komponen Livewire
                document.addEventListener('swal-success', event => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Biodata Anda berhasil diperbarui!',
                        icon: 'success',
                        confirmButtonText: 'Oke'
                    });
                });
            </script> --}}

            <script>
                // Listener untuk menangkap event dari komponen Livewire
                document.addEventListener('swal-success', event => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: event.detail.message,
                        icon: 'success',
                        confirmButtonText: 'Lanjutkan'
                    }).then((result) => {
                        // JIKA USER MENGKLIK TOMBOL "LANJUTKAN"
                        if (result.isConfirmed) {
                            // Lakukan redirect ke halaman upload dokumen
                            window.location.href = "{{ route('pendaftar.document.form') }}";
                        }
                    });
                });
            </script>
        @endpush
    </div>
