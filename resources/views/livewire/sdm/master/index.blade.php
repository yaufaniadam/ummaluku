<div>
    @section('title', 'Master Data SDM')
    @section('content_header')
        <h1>Master Data SDM</h1>
    @endsection

    <div class="card">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'ranks' ? 'active' : '' }}"
                       wire:click="$set('activeTab', 'ranks')" href="#">Pangkat/Golongan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'structural' ? 'active' : '' }}"
                       wire:click="$set('activeTab', 'structural')" href="#">Jabatan Struktural</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'functional' ? 'active' : '' }}"
                       wire:click="$set('activeTab', 'functional')" href="#">Jabatan Fungsional</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'statuses' ? 'active' : '' }}"
                       wire:click="$set('activeTab', 'statuses')" href="#">Status Kepegawaian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'documents' ? 'active' : '' }}"
                       wire:click="$set('activeTab', 'documents')" href="#">Jenis Dokumen</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="mb-3">
                <button wire:click="create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Nama</th>
                            @if($activeTab === 'ranks')
                                <th>Golongan</th>
                            @elseif($activeTab === 'structural' || $activeTab === 'functional')
                                <th>Kode</th>
                            @endif
                            @if($activeTab === 'functional')
                                <th>Tipe</th>
                            @endif
                            @if($activeTab === 'documents')
                                <th>Wajib?</th>
                            @endif
                            <th>Deskripsi</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}</td>
                                <td>{{ $item->name }}</td>

                                @if($activeTab === 'ranks')
                                    <td>{{ $item->grade }}</td>
                                @elseif($activeTab === 'structural' || $activeTab === 'functional')
                                    <td>{{ $item->code ?? '-' }}</td>
                                @endif

                                @if($activeTab === 'functional')
                                    <td>
                                        <span class="badge badge-{{ $item->type === 'academic' ? 'info' : 'secondary' }}">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    </td>
                                @endif

                                @if($activeTab === 'documents')
                                    <td>
                                        <span class="badge badge-{{ $item->is_mandatory ? 'danger' : 'success' }}">
                                            {{ $item->is_mandatory ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </td>
                                @endif

                                <td>{{ $item->description }}</td>
                                <td>
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $data->links() }}
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($isOpen)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editId ? 'Edit Data' : 'Tambah Data' }}</h5>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control @error('formData.name') is-invalid @enderror"
                                       wire:model="formData.name">
                                @error('formData.name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            @if($activeTab === 'ranks')
                                <div class="form-group">
                                    <label>Golongan (Ex: III/a)</label>
                                    <input type="text" class="form-control @error('formData.grade') is-invalid @enderror"
                                           wire:model="formData.grade">
                                    @error('formData.grade') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            @if($activeTab === 'structural' || $activeTab === 'functional')
                                <div class="form-group">
                                    <label>Kode (Opsional)</label>
                                    <input type="text" class="form-control @error('formData.code') is-invalid @enderror"
                                           wire:model="formData.code">
                                    @error('formData.code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            @if($activeTab === 'functional')
                                <div class="form-group">
                                    <label>Tipe</label>
                                    <select class="form-control @error('formData.type') is-invalid @enderror"
                                            wire:model="formData.type">
                                        <option value="academic">Akademik</option>
                                        <option value="non-academic">Non-Akademik</option>
                                    </select>
                                    @error('formData.type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            @if($activeTab === 'documents')
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="isMandatory"
                                           wire:model="formData.is_mandatory">
                                    <label class="form-check-label" for="isMandatory">Wajib?</label>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control @error('formData.description') is-invalid @enderror"
                                          wire:model="formData.description"></textarea>
                                @error('formData.description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="save">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($confirmingDeletion)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="close" wire:click="cancelDelete">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
