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
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Kode</th>
                            <th>Nama Unit</th>
                            <th>Tipe</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($workUnits as $unit)
                            <tr>
                                <td>{{ $loop->iteration + ($workUnits->currentPage() - 1) * $workUnits->perPage() }}</td>
                                <td>{{ $unit->code ?? '-' }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->type ?? '-' }}</td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $unit->id }})" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus unit ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $unit->id }})" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditMode ? 'Edit Unit Kerja' : 'Tambah Unit Kerja' }}</h5>
                    <button type="button" class="close" wire:click="$set('showModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
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
                                <option value="Bagian">Bagian</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="modal-footer p-0 mt-3">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                            <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Simpan Perubahan' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
