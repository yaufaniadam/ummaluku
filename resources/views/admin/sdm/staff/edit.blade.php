@extends('adminlte::page')

@section('title', 'Edit Tenaga Kependidikan')

@section('content_header')
    <h1>Edit Tenaga Kependidikan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.sdm.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            {{-- AKUN PENGGUNA --}}
                            <div class="col-md-6">
                                <h5><i class="fas fa-user-lock"></i> Akun Pengguna</h5>
                                <hr>
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        id="name" value="{{ old('name', $staff->user->name) }}" placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="photo">Foto Profil</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo" name="photo">
                                            <label class="custom-file-label" for="photo">Pilih file</label>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @if ($staff->user->profile_photo_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $staff->user->profile_photo_path) }}" alt="Foto Profil" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" value="{{ old('email', $staff->user->email) }}" placeholder="Masukkan email">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password (Biarkan kosong jika tidak ganti)</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        placeholder="Masukkan password baru">
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="password_confirmation" placeholder="Ulangi password baru">
                                </div>
                            </div>

                            {{-- DATA KEPEGAWAIAN --}}
                            <div class="col-md-6">
                                <h5><i class="fas fa-id-card"></i> Data Kepegawaian</h5>
                                <hr>
                                <div class="form-group">
                                    <label for="nip">NIP (Nomor Induk Pegawai)</label>
                                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                        id="nip" value="{{ old('nip', $staff->nip) }}" placeholder="Contoh: 19900101...">
                                    @error('nip')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="L" {{ old('gender', $staff->gender) == 'L' ? 'checked' : '' }}>
                                        <label class="form-check-label">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="P" {{ old('gender', $staff->gender) == 'P' ? 'checked' : '' }}>
                                        <label class="form-check-label">Perempuan</label>
                                    </div>
                                    @error('gender')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">No. HP</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" value="{{ old('phone', $staff->phone) }}" placeholder="Contoh: 0812...">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Tempat Lahir</label>
                                        <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place', $staff->birth_place) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $staff->birth_date) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Nama Bank</label>
                                        <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $staff->bank_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>No. Rekening</label>
                                        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number', $staff->account_number) }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $staff->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status Aktif</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status Kepegawaian</label>
                                    <select name="employment_status_id" class="form-control @error('employment_status_id') is-invalid @enderror">
                                        <option value="">-- Pilih Status --</option>
                                        @foreach($employmentStatuses as $status)
                                            <option value="{{ $status->id }}" {{ old('employment_status_id', $staff->employment_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('employment_status_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- PENEMPATAN --}}
                                <h5><i class="fas fa-building"></i> Penempatan</h5>
                                <hr>
                                @php
                                    $currentType = $staff->program_id ? 'prodi' : ($staff->work_unit_id ? 'bureau' : '');
                                @endphp
                                <div class="form-group">
                                    <label>Unit Penempatan</label>
                                    <select id="unit_type" name="unit_type" class="form-control @error('unit_type') is-invalid @enderror" onchange="toggleUnitSelect()">
                                        <option value="">-- Pilih Tipe Unit --</option>
                                        <option value="prodi" {{ old('unit_type', $currentType) == 'prodi' ? 'selected' : '' }}>Program Studi</option>
                                        <option value="bureau" {{ old('unit_type', $currentType) == 'bureau' ? 'selected' : '' }}>Biro / Unit Kerja</option>
                                    </select>
                                    @error('unit_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group" id="prodi_select" style="display: none;">
                                    <label>Pilih Program Studi</label>
                                    <select name="program_id" class="form-control @error('program_id') is-invalid @enderror">
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach($programs as $prog)
                                            <option value="{{ $prog->id }}" {{ old('program_id', $staff->program_id) == $prog->id ? 'selected' : '' }}>{{ $prog->name_id }}</option>
                                        @endforeach
                                    </select>
                                    @error('program_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group" id="bureau_select" style="display: none;">
                                    <label>Pilih Biro / Unit Kerja</label>
                                    <select name="work_unit_id" class="form-control @error('work_unit_id') is-invalid @enderror">
                                        <option value="">-- Pilih Biro --</option>
                                        @foreach($workUnits as $unit)
                                            <option value="{{ $unit->id }}" {{ old('work_unit_id', $staff->work_unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('work_unit_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.sdm.staff.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function toggleUnitSelect() {
        const type = document.getElementById('unit_type').value;
        document.getElementById('prodi_select').style.display = (type === 'prodi') ? 'block' : 'none';
        document.getElementById('bureau_select').style.display = (type === 'bureau') ? 'block' : 'none';
    }

    // Run on load to handle old input or existing data
    window.addEventListener('DOMContentLoaded', (event) => {
        toggleUnitSelect();
    });
</script>
@stop
