<div>
    @section('title', 'Manajemen User')

    @section('content_header')
        <h1>Manajemen User</h1>
    @stop

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'mahasiswa' ? 'active' : '' }}"
                               wire:click.prevent="setTab('mahasiswa')" href="#">Mahasiswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'dosen' ? 'active' : '' }}"
                               wire:click.prevent="setTab('dosen')" href="#">Dosen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'staf' ? 'active' : '' }}"
                               wire:click.prevent="setTab('staf')" href="#">Staf</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'camaru' ? 'active' : '' }}"
                               wire:click.prevent="setTab('camaru')" href="#">Camaru</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Cari User..." wire:model.live.debounce.300ms="search">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                        <div>
                             <a href="{{ route('admin.system.users.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>
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
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Nama
                                        @if ($sortColumn === 'name')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('email')" style="cursor: pointer;">
                                        Email
                                        @if ($sortColumn === 'email')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </th>
                                    <th>Role</th>
                                    <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                        Dibuat Pada
                                        @if ($sortColumn === 'created_at')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-info">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.system.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <button wire:click="delete({{ $user->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menghapus user ini?"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
