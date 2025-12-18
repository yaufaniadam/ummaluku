<div>
    {{-- Komentar: Karena ini komponen Livewire, semua elemen harus berada di dalam satu div utama. --}}

    <div class="alert alert-warning"><i class="bi bi-exclamation-octagon"></i> Silahkan lengkapi data-data berikut ini.</div>

    <form wire:submit.prevent="save">
        <div class="row row-cards">

            <div class="col-md-12">
                <div class="card card-stacked">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Data Diri Calon Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nama Lengkap <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model.defer="name" placeholder="Sesuai Ijazah">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Alamat Email Aktif <sup class="text-danger">*</sup></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model.defer="email" placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nomor Telepon (WhatsApp) <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    wire:model.live="phone" placeholder="08...">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">No. Telepon Orang Tua/Wali <sup class="text-danger">*</sup></label>
                                <input type="text"
                                    class="form-control @error('parent_phone') is-invalid @enderror" 
                                    wire:model.live="parent_phone" placeholder="08...">
                                @error('parent_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Jenis Kelamin <sup class="text-danger">*</sup></label>
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
                            <div class="col-md-4 mb-3" wire:ignore>
                                <label class="form-label required">Tempat Lahir <sup class="text-danger">*</sup></label>
                                <select id="birth_place_select" class="form-select @error('birth_place') is-invalid @enderror"
                                    wire:model.defer="birth_place">
                                    @if($birth_place)
                                        <option value="{{ $birth_place }}" selected>{{ $birth_place }}</option>
                                    @endif
                                </select>
                                @error('birth_place')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Tanggal Lahir <sup class="text-danger">*</sup></label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                    wire:model.defer="birth_date">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label required">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                    wire:model.defer="id_number">
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label required">NISN (Nomor Induk Siswa Nasional)</label>
                                <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                    wire:model.defer="nisn">
                                @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            {{-- <div class="col-md-12 mb-3">
                                <label class="form-label required">Alamat Lengkap</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" wire:model.defer="address" rows="3"></textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3 mb-3">
                <div class="card card-stacked">
                    <div class="card-header">
                        Pilihan Program Studi
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-12 mb-3">
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
                            </div> --}}
                            {{-- <div class="col-md-12 mb-3">
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
                            </div> --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Pilihan Program Studi 1 <sup class="text-danger">*</sup></label>
                                <select class="form-select @error('program_choice_1') is-invalid @enderror"
                                    wire:model.live="program_choice_1">
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
                                    wire:model.live="program_choice_2">
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

        </div>

        {{-- Tombol Submit --}}
        <div class="d-flex justify-content-end mt-4 pb-5">
            <button type="submit" class="btn btn-warning btn-lg">
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
    @push('js')
    <script>
        $(document).ready(function() {
            function initSelect2() {
                $('#birth_place_select').select2({
                    placeholder: '-- Pilih Tempat Lahir --',
                    allowClear: true,
                    minimumInputLength: 3,
                    ajax: {
                        url: '{{ route("api.locations.cities") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.results
                            };
                        },
                        cache: true
                    }
                });
            }

            initSelect2();

            $('#birth_place_select').on('change', function (e) {
                var data = $('#birth_place_select').select2("val");
                @this.set('birth_place', data);
            });

            // Re-init select2 after Livewire updates if necessary
            // (though wire:ignore should handle the container)
            Livewire.on('re-init-select2', () => {
                initSelect2();
            });
        });
    </script>
    @endpush
</div>
