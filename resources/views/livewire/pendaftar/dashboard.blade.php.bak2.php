<div>
@section('title', 'Dashboard Pendaftar')
@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Dashboard Pendaftaran: {{ $application->prospective->user->name }}</h2>

        {{-- Form untuk Melengkapi Biodata (Livewire) --}}
        <div class="card card-body mb-4">
            <form wire:submit.prevent="saveBiodata">
                <h3 class="card-title mb-4">Lengkapi Biodata Anda</h3>
                <p class="text-muted mb-4">Pastikan semua data yang ditandai dengan bintang (*) diisi dengan benar untuk
                    melanjutkan ke tahap berikutnya.</p>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                            wire:model.defer="nisn" placeholder="Masukkan NISN Anda...">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                            <label class="form-label required">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                wire:model.defer="id_number" placeholder="Masukkan 16 digit NIK...">
                            @error('id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-12 mb-3">
                            <label class="form-label required">Alamat Lengkap (Sesuai KTP)</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" wire:model.defer="address" rows="3"
                                placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kel. ..."></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -
                </div>

                <hr class="my-4">

                {{-- Data Orang Tua --}}
                <h4 class="mb-3">Data Orang Tua</h4>
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
                            <input type="text" class="form-control @error('father_occupation') is-invalid @enderror"
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
                            <input type="text" class="form-control @error('mother_occupation') is-invalid @enderror"
                                wire:model.defer="mother_occupation">
                            @error('mother_occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label required">No. Telepon Orang Tua/Wali</label>
                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror"
                                wire:model.defer="parent_phone" placeholder="Gunakan nomor yang aktif">
                            @error('parent_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}

                <hr class="my-4">

                {{-- Data Wali (Opsional) --}}
                <h4 class="mb-3">Data Wali <small class="text-muted">(Isi jika tinggal bersama wali)</small></h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Wali</label>
                            <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                                wire:model.defer="guardian_name">
                            @error('guardian_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan Wali</label>
                            <input type="text" class="form-control @error('guardian_occupation') is-invalid @enderror"
                                wire:model.defer="guardian_occupation">
                            @error('guardian_occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">No. Telepon Wali</label>
                            <input type="text" class="form-control @error('guardian_phone') is-invalid @enderror"
                                wire:model.defer="guardian_phone">
                            @error('guardian_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> 

                {{-- Tombol Aksi --}}
                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary ms-auto">
                        <div wire:loading.remove wire:target="saveBiodata">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
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

        {{-- Tabel untuk Upload Dokumen (Form Blade Biasa) --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Dokumen Persyaratan</h3>
            </div>
            <div class="card-body p-0">
                {{-- Di sini kita letakkan kode tabel upload dokumen non-livewire kita --}}
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Listener untuk SweetAlert tetap kita perlukan --}}
    <script>
        document.addEventListener('swal-success', event => {
        Swal.fire({
            title: event.detail.type === 'success' ? 'Berhasil!' : 'Oops!',
            text: event.detail.message,
            icon: event.detail.type,
            confirmButtonText: 'Oke'
        });
    });
    </script>
@endpush
</div>