<div>
    @section('title', 'Manajemen Role')

    @section('content_header')
        <h1>Manajemen Role</h1>
    @stop

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Role</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.system.roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Role
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Cari Role..." wire:model.live.debounce.300ms="search">
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama Role</th>
                                    <th>Guard</th>
                                    <th>Jumlah Permission</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $index => $role)
                                    <tr>
                                        <td>{{ $roles->firstItem() + $index }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->guard_name }}</td>
                                        <td>{{ $role->permissions_count ?? $role->permissions->count() }}</td>
                                        <td>
                                            <a href="{{ route('admin.system.roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <button wire:click="delete({{ $role->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus role ini?"
                                                    class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data role.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
