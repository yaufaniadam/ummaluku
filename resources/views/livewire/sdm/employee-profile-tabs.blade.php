<div>
    <div class="card">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'structural' ? 'active' : '' }}"
                       wire:click="setTab('structural')" href="javascript:void(0)">
                       <i class="fas fa-sitemap mr-1"></i> Jabatan Struktural
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'functional' ? 'active' : '' }}"
                       wire:click="setTab('functional')" href="javascript:void(0)">
                       <i class="fas fa-briefcase mr-1"></i> Jabatan Fungsional
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'rank' ? 'active' : '' }}"
                       wire:click="setTab('rank')" href="javascript:void(0)">
                       <i class="fas fa-layer-group mr-1"></i> Pangkat/Golongan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'documents' ? 'active' : '' }}"
                       wire:click="setTab('documents')" href="javascript:void(0)">
                       <i class="fas fa-file-alt mr-1"></i> Dokumen
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <button wire:click="create" class="btn btn-primary btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Data
            </button>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        @if ($activeTab === 'structural')
                            <tr>
                                <th>Jabatan</th>
                                <th>Unit Kerja</th>
                                <th>No SK</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        @elseif ($activeTab === 'functional')
                            <tr>
                                <th>Jabatan</th>
                                <th>No SK</th>
                                <th>TMT</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        @elseif ($activeTab === 'rank')
                            <tr>
                                <th>Pangkat/Golongan</th>
                                <th>No SK</th>
                                <th>TMT</th>
                                <th>Masa Kerja</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        @elseif ($activeTab === 'documents')
                            <tr>
                                <th>Jenis Dokumen</th>
                                <th>Nama File</th>
                                <th>Deskripsi</th>
                                <th>Tgl Upload</th>
                                <th>Aksi</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @forelse ($histories as $item)
                            <tr>
                                @if ($activeTab === 'structural')
                                    <td>{{ $item->structuralPosition->name }}</td>
                                    <td>{{ $item->workUnit->name ?? '-' }}</td>
                                    <td>{{ $item->sk_number }}</td>
                                    <td>{{ $item->start_date ? $item->start_date->format('d M Y') : '-' }}</td>
                                    <td>{{ $item->end_date ? $item->end_date->format('d M Y') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                @elseif ($activeTab === 'functional')
                                    <td>{{ $item->functionalPosition->name }}</td>
                                    <td>{{ $item->sk_number }}</td>
                                    <td>{{ $item->tmt ? $item->tmt->format('d M Y') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                @elseif ($activeTab === 'rank')
                                    <td>{{ $item->employeeRank->name }} ({{ $item->employeeRank->grade }})</td>
                                    <td>{{ $item->sk_number }}</td>
                                    <td>{{ $item->tmt ? $item->tmt->format('d M Y') : '-' }}</td>
                                    <td>{{ $item->years_of_service }} Thn {{ $item->months_of_service }} Bln</td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                @elseif ($activeTab === 'documents')
                                    <td>{{ $item->documentType->name }}</td>
                                    <td>
                                        <a href="{{ Storage::url($item->file_path) }}" target="_blank">
                                            {{ $item->file_name }} <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                @endif

                                <td>
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-xs btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if ($isOpen)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editId ? 'Edit' : 'Tambah' }} Data</h5>
                        <button type="button" class="close" wire:click="$set('isOpen', false)">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">

                            {{-- Structural Form --}}
                            @if ($activeTab === 'structural')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Jabatan</label>
                                        <select class="form-control @error('formData.structural_position_id') is-invalid @enderror"
                                                wire:model="formData.structural_position_id">
                                            <option value="">Pilih Jabatan...</option>
                                            @foreach ($structuralPositions as $pos)
                                                <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('formData.structural_position_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Unit Kerja</label>
                                        <select class="form-control @error('formData.work_unit_id') is-invalid @enderror"
                                                wire:model="formData.work_unit_id">
                                            <option value="">Pilih Unit Kerja (Opsional)...</option>
                                            @foreach ($workUnits as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Nomor SK</label>
                                        <input type="text" class="form-control" wire:model="formData.sk_number">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Mulai</label>
                                        <input type="date" class="form-control" wire:model="formData.start_date">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Selesai</label>
                                        <input type="date" class="form-control" wire:model="formData.end_date">
                                    </div>
                                    <div class="col-md-12 form-check">
                                        <input type="checkbox" class="form-check-input" id="isActive" wire:model="formData.is_active">
                                        <label class="form-check-label" for="isActive">Masih Menjabat (Aktif)</label>
                                    </div>
                                </div>

                            {{-- Functional Form --}}
                            @elseif ($activeTab === 'functional')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Jabatan Fungsional</label>
                                        <select class="form-control @error('formData.functional_position_id') is-invalid @enderror"
                                                wire:model="formData.functional_position_id">
                                            <option value="">Pilih...</option>
                                            @foreach ($functionalPositions as $pos)
                                                <option value="{{ $pos->id }}">{{ $pos->name }} ({{ ucfirst($pos->type) }})</option>
                                            @endforeach
                                        </select>
                                        @error('formData.functional_position_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Nomor SK</label>
                                        <input type="text" class="form-control" wire:model="formData.sk_number">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>TMT (Terhitung Mulai Tanggal)</label>
                                        <input type="date" class="form-control" wire:model="formData.tmt">
                                    </div>
                                    <div class="col-md-12 form-check">
                                        <input type="checkbox" class="form-check-input" id="isActive" wire:model="formData.is_active">
                                        <label class="form-check-label" for="isActive">Aktif</label>
                                    </div>
                                </div>

                            {{-- Rank Form --}}
                            @elseif ($activeTab === 'rank')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Pangkat/Golongan</label>
                                        <select class="form-control @error('formData.employee_rank_id') is-invalid @enderror"
                                                wire:model="formData.employee_rank_id">
                                            <option value="">Pilih...</option>
                                            @foreach ($ranks as $rank)
                                                <option value="{{ $rank->id }}">{{ $rank->grade }} - {{ $rank->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('formData.employee_rank_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Nomor SK</label>
                                        <input type="text" class="form-control" wire:model="formData.sk_number">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>TMT</label>
                                        <input type="date" class="form-control" wire:model="formData.tmt">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Masa Kerja (Thn)</label>
                                        <input type="number" class="form-control" wire:model="formData.years_of_service">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Masa Kerja (Bln)</label>
                                        <input type="number" class="form-control" wire:model="formData.months_of_service">
                                    </div>
                                    <div class="col-md-12 form-check">
                                        <input type="checkbox" class="form-check-input" id="isActive" wire:model="formData.is_active">
                                        <label class="form-check-label" for="isActive">Aktif</label>
                                    </div>
                                </div>

                            {{-- Document Form --}}
                            @elseif ($activeTab === 'documents')
                                <div class="form-group">
                                    <label>Jenis Dokumen</label>
                                    <select class="form-control @error('formData.employee_document_type_id') is-invalid @enderror"
                                            wire:model="formData.employee_document_type_id">
                                        <option value="">Pilih...</option>
                                        @foreach ($documentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }} {{ $type->is_mandatory ? '(Wajib)' : '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('formData.employee_document_type_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                @if(!$editId)
                                    <div class="form-group">
                                        <label>File Upload</label>
                                        <input type="file" class="form-control-file @error('uploadFile') is-invalid @enderror"
                                               wire:model="uploadFile">
                                        @error('uploadFile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Deskripsi/Keterangan</label>
                                    <textarea class="form-control" wire:model="formData.description"></textarea>
                                </div>
                            @endif

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('isOpen', false)">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="save">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation --}}
    @if($confirmingDeletion)
         <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="close" wire:click="$set('confirmingDeletion', false)">&times;</button>
                    </div>
                    <div class="modal-body">Yakin ingin menghapus data ini?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingDeletion', false)">Batal</button>
                        <button class="btn btn-danger" wire:click="delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
