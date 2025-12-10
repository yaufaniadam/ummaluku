@section('title', $roleId ? 'Edit Role' : 'Tambah Role')

@section('content_header')
    <h1>{{ $roleId ? 'Edit Role' : 'Tambah Role' }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $roleId ? 'Edit Data Role' : 'Form Tambah Role' }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.system.roles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form wire:submit.prevent="save">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name" placeholder="Contoh: Super Admin">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="guard_name">Guard Name</label>
                            <input type="text" class="form-control @error('guard_name') is-invalid @enderror" id="guard_name" wire:model="guard_name" placeholder="web">
                            @error('guard_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" type="checkbox" id="selectAll" wire:model.live="selectAll">
                                <label for="selectAll" class="custom-control-label font-weight-bold">Pilih Semua</label>
                            </div>
                            <hr>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="perm_{{ $permission->id }}" value="{{ $permission->name }}" wire:model="selectedPermissions">
                                            <label for="perm_{{ $permission->id }}" class="custom-control-label">{{ $permission->name }}</label>
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
@stop

@section('css')
@stop

@section('js')
@stop
