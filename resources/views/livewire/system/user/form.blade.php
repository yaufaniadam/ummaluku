<div>
    @section('title', $userId ? 'Edit User' : 'Tambah User')

    @section('content_header')
        <h1>{{ $userId ? 'Edit User' : 'Tambah User' }}</h1>
    @stop

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $userId ? 'Edit Data User' : 'Form Tambah User' }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.system.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form wire:submit.prevent="save">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name" placeholder="Nama Lengkap">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="Email">
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password {{ $userId ? '(Kosongkan jika tidak ingin mengubah)' : '' }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password" placeholder="Password">
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" wire:model="password_confirmation" placeholder="Ulangi Password">
                        </div>

                        <div class="form-group">
                            <label>Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="role_{{ $role->id }}" value="{{ $role->name }}" wire:model="selectedRoles">
                                            <label for="role_{{ $role->id }}" class="custom-control-label">{{ $role->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
