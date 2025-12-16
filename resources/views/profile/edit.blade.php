@extends('adminlte::page')

@section('title', 'Edit Profil')

@section('content_header')
    <h1>Edit Profil</h1>
@stop

@section('content')
    <div class="row">
        {{-- Card: Edit Informasi Profil --}}
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Informasi Profil</h3>
                </div>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="card-body">
                        {{-- Nama --}}
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Foto Profil --}}
                        <div class="form-group">
                            <label for="photo">Foto Profil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo" name="photo">
                                    <label class="custom-file-label" for="photo">Pilih file</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG. Maks: 1MB.</small>
                            @error('photo')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror

                            @if($user->profile_photo_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                         class="img-circle elevation-2"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         alt="Current Profile Photo">
                                    <p class="text-muted text-sm mt-1">Foto saat ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success ml-2 fade-out"><i class="fas fa-check"></i> Profil diperbarui.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Card: Update Password --}}
        <div class="col-md-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Ganti Password</h3>
                </div>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                                   autocomplete="current-password">
                            @if($errors->updatePassword->has('current_password'))
                                <span class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif"
                                   autocomplete="new-password">
                            @if($errors->updatePassword->has('password'))
                                <span class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                                   autocomplete="new-password">
                            @if($errors->updatePassword->has('password_confirmation'))
                                <span class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Ganti Password</button>

                        @if (session('status') === 'password-updated')
                            <span class="text-success ml-2 fade-out"><i class="fas fa-check"></i> Password berhasil diubah.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Custom File Input Label
        $(function () {
            bsCustomFileInput.init();
        });

        // Fade out success messages
        setTimeout(function() {
            $('.fade-out').fadeOut('slow');
        }, 3000);
    </script>
@stop
