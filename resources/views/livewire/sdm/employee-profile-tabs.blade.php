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
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'education' ? 'active' : '' }}"
                       wire:click="setTab('education')" href="javascript:void(0)">
                       <i class="fas fa-graduation-cap mr-1"></i> Riwayat Pendidikan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'inpassing' ? 'active' : '' }}"
                       wire:click="setTab('inpassing')" href="javascript:void(0)">
                       <i class="fas fa-award mr-1"></i> Inpasing
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

            @if(!$isReadOnly && (!$isSelfService || ($isSelfService && $activeTab === 'documents')))
                <button wire:click="create" class="btn btn-primary btn-sm mb-3">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            @endif

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
                        @elseif ($activeTab === 'education')
                            <tr>
                                <th>Jenjang</th>
                                <th>Institusi</th>
                                <th>Tahun Lulus</th>
                                <th>Jurusan</th>
                                <th>Ijazah</th>
                                <th>Aksi</th>
                            </tr>
                        @elseif ($activeTab === 'inpassing')
                            <tr>
                                <th>Kepangkatan</th>
                                <th>No. SK</th>
                                <th>Tgl. SK</th>
                                <th>TMT</th>
                                <th>Dokumen SK</th>
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
                                @elseif ($activeTab === 'education')
                                    <td>
                                        <span class="badge badge-primary">{{ $item->education_level }}</span>
                                    </td>
                                    <td>{{ $item->institution_name }}</td>
                                    <td>{{ $item->graduation_year }}</td>
                                    <td>{{ $item->major ?? '-' }}</td>
                                    <td>
                                        @if($item->certificate_path)
                                            <a href="{{ Storage::url($item->certificate_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file-pdf"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @elseif ($activeTab === 'inpassing')
                                    <td>{{ $item->employeeRank->name }} ({{ $item->employeeRank->grade }})</td>
                                    <td>{{ $item->sk_number ?? '-' }}</td>
                                    <td>{{ $item->sk_date ? $item->sk_date->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $item->tmt ? $item->tmt->format('d-m-Y') : '-' }}</td>
                                    <td>
                                        @if($item->document_path)
                                            <a href="{{ Storage::url($item->document_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file-pdf"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endif

                                <td>
                                    @if(!$isReadOnly && (!$isSelfService || ($isSelfService && $activeTab === 'documents')))
                                        <button wire:click="edit({{ $item->id }})" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
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

                            {{-- Education Form --}}
                            @elseif ($activeTab === 'education')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Jenjang Pendidikan <span class="text-danger">*</span></label>
                                        <select class="form-control @error('formData.education_level') is-invalid @enderror"
                                                wire:model="formData.education_level">
                                            <option value="">Pilih Jenjang...</option>
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                            <option value="D3">D3</option>
                                            <option value="D4">D4</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                        @error('formData.education_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Tahun Lulus <span class="text-danger">*</span></label>
                                        <input type="number" min="1950" max="{{ date('Y') + 10 }}"
                                               class="form-control @error('formData.graduation_year') is-invalid @enderror"
                                               wire:model="formData.graduation_year"
                                               placeholder="{{ date('Y') }}">
                                        @error('formData.graduation_year') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label>Nama Institusi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('formData.institution_name') is-invalid @enderror"
                                               wire:model="formData.institution_name"
                                               placeholder="Contoh: Universitas Indonesia">
                                        @error('formData.institution_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label>Jurusan/Bidang Studi</label>
                                        <input type="text" class="form-control @error('formData.major') is-invalid @enderror"
                                               wire:model="formData.major"
                                               placeholder="Contoh: Teknik Informatika (opsional)">
                                        @error('formData.major') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label>Upload Ijazah (PDF)</label>
                                        <input type="file" accept=".pdf"
                                               class="form-control-file @error('uploadFile') is-invalid @enderror"
                                               wire:model="uploadFile">
                                        <small class="form-text text-muted">Format: PDF, Max 5MB (Opsional)</small>
                                        @error('uploadFile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        
                                        @if($editId && $formData['certificate_path'] ?? false)
                                            <div class="mt-2">
                                                <small class="text-info">
                                                    <i class="fas fa-info-circle"></i> Ijazah saat ini: 
                                                    <a href="{{ Storage::url($formData['certificate_path']) }}" target="_blank">Lihat <i class="fas fa-external-link-alt"></i></a>
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            {{-- Inpasing Form --}}
                            @elseif ($activeTab === 'inpassing')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Pangkat/Golongan <span class="text-danger">*</span></label>
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
                                        <input type="text" class="form-control @error('formData.sk_number') is-invalid @enderror"
                                               wire:model="formData.sk_number"
                                               placeholder="Nomor SK (opsional)">
                                        @error('formData.sk_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Tanggal SK</label>
                                        <input type="date" class="form-control @error('formData.sk_date') is-invalid @enderror"
                                               wire:model="formData.sk_date">
                                        @error('formData.sk_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>TMT (Terhitung Mulai Tanggal) <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('formData.tmt') is-invalid @enderror"
                                               wire:model="formData.tmt">
                                        @error('formData.tmt') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label>Upload Dokumen SK (PDF)</label>
                                        <input type="file" accept=".pdf"
                                               class="form-control-file @error('uploadFile') is-invalid @enderror"
                                               wire:model="uploadFile">
                                        <small class="form-text text-muted">Format: PDF, Max 5MB (Opsional)</small>
                                        @error('uploadFile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        
                                        @if($editId && $formData['document_path'] ?? false)
                                            <div class="mt-2">
                                                <small class="text-info">
                                                    <i class="fas fa-info-circle"></i> Dokumen saat ini: 
                                                    <a href="{{ Storage::url($formData['document_path']) }}" target="_blank">Lihat <i class="fas fa-external-link-alt"></i></a>
                                                </small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-12 form-check">
                                        <input type="checkbox" class="form-check-input" id="isActive" wire:model="formData.is_active">
                                        <label class="form-check-label" for="isActive">Aktif</label>
                                    </div>
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
