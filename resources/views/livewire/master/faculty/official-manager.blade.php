<div>
    @section('title', 'Kelola Pejabat Fakultas')
    @section('content_header')
        <h1>Kelola Pejabat: {{ $faculty->name_id }}</h1>
    @endsection

    <div class="row">
        <!-- Form Section -->
        <div class="col-md-4">
            <div class="{{ $isEditing ? 'card card-warning' : 'card card-primary' }}">
                <div class="card-header">
                    <h3 class="card-title">{{ $isEditing ? 'Edit Data Pejabat' : 'Tetapkan Pejabat Baru' }}</h3>
                </div>
                <form wire:submit.prevent="assignOfficial">
                    <div class="card-body">
                        <!-- Position Selection -->
                        <div class="form-group">
                            <label>Jabatan</label>
                            <select wire:model.live="position" class="form-control" {{ $isEditing ? 'disabled' : '' }}>
                                <option value="Dekan">Dekan</option>
                                <option value="Kepala Tata Usaha">Kepala Tata Usaha (KTU)</option>
                                <option value="Kepala Bagian Administrasi Akademik">Kepala Bagian Administrasi Akademik</option>
                            </select>
                            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Employee Selection -->
                        <div class="form-group">
                            <label>
                                @if($position === 'Dekan')
                                    Pilih Dosen
                                @else
                                    Pilih Tenaga Kependidikan (Staff)
                                @endif
                            </label>

                            <select wire:model="employee_id" class="form-control" {{ $isEditing ? 'disabled' : '' }}>
                                <option value="">-- Pilih --</option>
                                @if($position === 'Dekan')
                                    @foreach($available_lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }} ({{ $lecturer->nidn }})</option>
                                    @endforeach
                                @else
                                    @foreach($available_staff as $staff)
                                        <option value="{{ $staff->id }}">
                                            {{ $staff->user->name ?? '-' }} ({{ $staff->nip }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Date & SK -->
                        <div class="form-group">
                            <label>Tanggal Mulai Menjabat</label>
                            <input type="date" wire:model="start_date" class="form-control">
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if($isEditing)
                            <div class="form-group">
                                <label>Tanggal Selesai (Opsional)</label>
                                <input type="date" wire:model="edit_end_date" class="form-control @error('edit_end_date') is-invalid @enderror">
                                <small class="text-muted">Isi jika masa jabatan sudah berakhir.</small>
                                @error('edit_end_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                             <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="isActiveSwitch" wire:model="edit_is_active">
                                    <label class="custom-control-label" for="isActiveSwitch">Status Aktif</label>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Nomor SK</label>
                            <input type="text" wire:model="sk_number" class="form-control" placeholder="Contoh: SK-123/2025">
                            @error('sk_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                         @if($isEditing)
                            <button type="button" wire:click="cancelEdit" class="btn btn-default">Batal</button>
                            <button type="submit" class="btn btn-warning">Update Perubahan</button>
                        @else
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            @if(!$isEditing)
                <a href="{{ route('master.faculties.index') }}" class="btn btn-default btn-block mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Fakultas
                </a>
            @endif
        </div>

        <!-- History Section (Tabbed) -->
        <div class="col-md-8">
            <div class="card card-outline card-info">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="official-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="dekan-tab" data-toggle="pill" href="#dekan-content" role="tab" aria-controls="dekan-content" aria-selected="true">Dekan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ktu-tab" data-toggle="pill" href="#ktu-content" role="tab" aria-controls="ktu-content" aria-selected="false">Kepala Tata Usaha</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="kabag-tab" data-toggle="pill" href="#kabag-content" role="tab" aria-controls="kabag-content" aria-selected="false">Kabag Adm. Akademik</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="official-tabs-content">

                        <!-- Dekan Tab -->
                        <div class="tab-pane fade show active" id="dekan-content" role="tabpanel" aria-labelledby="dekan-tab">
                            <div class="table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Status</th>
                                            <th>SK</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deans as $official)
                                            <tr class="{{ $official->is_active ? 'table-success' : '' }}">
                                                <td>
                                                    {{ $official->employee->full_name_with_degree ?? $official->employee->user->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $official->start_date->format('d M Y') }}</td>
                                                <td>{{ $official->end_date ? $official->end_date->format('d M Y') : '-' }}</td>
                                                <td>
                                                    @if($official->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Non-Aktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $official->sk_number ?? '-' }}</td>
                                                <td>
                                                    <button wire:click="edit({{ $official->id }})" class="btn btn-xs btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus data ini? Data historis SDM juga akan terhapus.') || event.stopImmediatePropagation()" wire:click="delete({{ $official->id }})" class="btn btn-xs btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center text-muted">Belum ada riwayat.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- KTU Tab -->
                        <div class="tab-pane fade" id="ktu-content" role="tabpanel" aria-labelledby="ktu-tab">
                            <div class="table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Status</th>
                                            <th>SK</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ktus as $official)
                                            <tr class="{{ $official->is_active ? 'table-success' : '' }}">
                                                <td>
                                                    {{ $official->employee->full_name_with_degree ?? $official->employee->user->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $official->start_date->format('d M Y') }}</td>
                                                <td>{{ $official->end_date ? $official->end_date->format('d M Y') : '-' }}</td>
                                                <td>
                                                    @if($official->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Non-Aktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $official->sk_number ?? '-' }}</td>
                                                <td>
                                                    <button wire:click="edit({{ $official->id }})" class="btn btn-xs btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus data ini? Data historis SDM juga akan terhapus.') || event.stopImmediatePropagation()" wire:click="delete({{ $official->id }})" class="btn btn-xs btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center text-muted">Belum ada riwayat.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Kabag Tab -->
                        <div class="tab-pane fade" id="kabag-content" role="tabpanel" aria-labelledby="kabag-tab">
                            <div class="table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Status</th>
                                            <th>SK</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kabags as $official)
                                            <tr class="{{ $official->is_active ? 'table-success' : '' }}">
                                                <td>
                                                    {{ $official->employee->full_name_with_degree ?? $official->employee->user->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $official->start_date->format('d M Y') }}</td>
                                                <td>{{ $official->end_date ? $official->end_date->format('d M Y') : '-' }}</td>
                                                <td>
                                                    @if($official->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Non-Aktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $official->sk_number ?? '-' }}</td>
                                                <td>
                                                    <button wire:click="edit({{ $official->id }})" class="btn btn-xs btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus data ini? Data historis SDM juga akan terhapus.') || event.stopImmediatePropagation()" wire:click="delete({{ $official->id }})" class="btn btn-xs btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center text-muted">Belum ada riwayat.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        window.addEventListener('alert', event => {
            toastr[event.detail.type](event.detail.message);
        });
    </script>
    @endpush
</div>
