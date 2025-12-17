<div>
    @section('title', 'Master Unit Kerja')

    @section('content_header')
        <h1>Master Unit Kerja</h1>
    @stop

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Unit Kerja</h3>
            <div class="card-tools">
                <button wire:click="create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Unit
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Cari Nama / Kode...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Unit</th>
                            <th>Tipe</th>
                            <th>Pejabat Saat Ini</th>
                            <th width="250" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($workUnits as $unit)
                            {{-- Parent Row --}}
                            <tr class="table-active">
                                <td><strong>{{ $unit->code ?? '-' }}</strong></td>
                                <td>
                                    <strong>{{ $unit->name }}</strong>
                                    @if($unit->children->count() > 0)
                                        <span class="badge badge-info ml-2">{{ $unit->children->count() }} Divisi</span>
                                    @endif
                                </td>
                                <td>{{ $unit->type ?? '-' }}</td>
                                <td>
                                    @php $head = $unit->currentHead(); @endphp
                                    @if($head && $head->employee)
                                        {{ $head->employee_type == \App\Models\Lecturer::class ? ($head->employee->full_name_with_degree ?? $head->employee->user->name) : $head->employee->user->name }}
                                        <br><small class="text-muted">{{ $head->position }}</small>
                                    @else
                                        <span class="text-muted text-sm font-italic">Belum ada pejabat</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('master.work-units.manage-officials', $unit->id) }}" class="btn btn-info btn-sm" title="Kelola Pejabat">
                                            <i class="fas fa-user-tie"></i> Pejabat
                                        </a>
                                        @if($unit->type == 'Lembaga' || $unit->type == 'Unit Pendukung')
                                            <button wire:click="createChild({{ $unit->id }})" class="btn btn-success btn-sm" title="Tambah Divisi">
                                                <i class="fas fa-plus"></i> Divisi
                                            </button>
                                        @endif
                                        <button wire:click="edit({{ $unit->id }})" class="btn btn-warning btn-sm" title="Edit Unit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirm('Apakah Anda yakin ingin menghapus unit ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $unit->id }})" class="btn btn-danger btn-sm" title="Hapus Unit">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Children Rows --}}
                            @foreach($unit->children as $child)
                                <tr>
                                    <td class="pl-4 text-muted"><i class="fas fa-level-up-alt fa-rotate-90 mr-2"></i> {{ $child->code ?? '-' }}</td>
                                    <td class="pl-4">{{ $child->name }}</td>
                                    <td>{{ $child->type ?? '-' }}</td>
                                    <td>
                                        @php $childHead = $child->currentHead(); @endphp
                                        @if($childHead && $childHead->employee)
                                             {{ $childHead->employee_type == \App\Models\Lecturer::class ? ($childHead->employee->full_name_with_degree ?? $childHead->employee->user->name) : $childHead->employee->user->name }}
                                            <br><small class="text-muted">{{ $childHead->position }}</small>
                                        @else
                                            <span class="text-muted text-sm font-italic">Belum ada pejabat</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('master.work-units.manage-officials', $child->id) }}" class="btn btn-info btn-xs" title="Kelola Pejabat">
                                                <i class="fas fa-user-tie"></i>
                                            </a>
                                            <button wire:click="edit({{ $child->id }})" class="btn btn-warning btn-xs" title="Edit Unit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="confirm('Apakah Anda yakin ingin menghapus divisi ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $child->id }})" class="btn btn-danger btn-xs" title="Hapus Unit">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $workUnits->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Create/Edit --}}
    @if($showModal)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5); overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditMode ? 'Edit Unit Kerja' : ($parentId ? 'Tambah Divisi' : 'Tambah Unit Kerja') }}
                    </h5>
                    <button type="button" class="close" wire:click="$set('showModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                        @if($parentId && !$isEditMode)
                            <div class="alert alert-info">
                                Menambahkan divisi di bawah: <strong>{{ \App\Models\WorkUnit::find($parentId)->name }}</strong>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Kode Unit</label>
                            <input type="text" wire:model="code" class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: BAAK">
                            @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Unit <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Biro Administrasi Akademik">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Tipe Unit</label>
                            <select wire:model="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Biro">Biro</option>
                                <option value="UPT">UPT</option>
                                <option value="Lembaga">Lembaga</option>
                                <option value="Divisi">Divisi</option>
                                <option value="Bagian">Bagian</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="modal-footer p-0 mt-3 d-flex justify-content-between">
                            <div>
                                @if($isEditMode)
                                <a href="{{ route('master.work-units.manage-officials', $workUnitId) }}" class="btn btn-info">
                                    <i class="fas fa-user-tie"></i> Kelola Pejabat
                                </a>
                                @endif
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                                <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Simpan Perubahan' : 'Simpan' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
