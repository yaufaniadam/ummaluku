<div>
    @section('title', 'Kelola Pejabat Unit Kerja')
    @section('content_header')
        <h1>Kelola Pejabat: {{ $workUnit->name }}</h1>
    @stop

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $isEditing ? 'Edit Pejabat' : 'Tambah Pejabat Baru' }}</h3>
                </div>
                <div class="card-body">
                    @if(!$isEditing)
                    <div class="form-group">
                        <label>Tipe Pegawai</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" id="type_lecturer" wire:model.live="employee_type" value="lecturer">
                                <label for="type_lecturer" class="custom-control-label">Dosen</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="type_staff" wire:model.live="employee_type" value="staff">
                                <label for="type_staff" class="custom-control-label">Tendik (Staf)</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Nama Pegawai</label>
                        @if($isEditing)
                             <input type="text" class="form-control" value="{{ $employee_type == 'lecturer' ? \App\Models\Lecturer::find($employee_id)?->full_name_with_degree : \App\Models\Staff::find($employee_id)?->user->name }}" disabled>
                        @else
                        <select wire:model="employee_id" class="form-control">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $employee_type === 'lecturer' ? ($emp->full_name_with_degree ?? $emp->user->name) : $emp->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                        @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nomor SK</label>
                        <input type="text" wire:model="sk_number" class="form-control">
                        @error('sk_number') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" wire:model="start_date" class="form-control">
                        @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    @if($isEditing)
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" wire:model="edit_end_date" class="form-control">
                        @error('edit_end_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="isActiveSwitch" wire:model="edit_is_active">
                            <label class="custom-control-label" for="isActiveSwitch">Aktif</label>
                        </div>
                    </div>
                    @endif

                    <div class="form-group mt-4">
                        <button wire:click="assignOfficial" class="btn btn-primary btn-block">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Tetapkan Pejabat' }}
                        </button>
                        @if($isEditing)
                            <button wire:click="cancelEdit" class="btn btn-secondary btn-block">Batal</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pejabat</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Periode</th>
                                <th>SK</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($officials as $official)
                                <tr>
                                    <td>
                                        @if($official->employee)
                                            {{ $official->employee_type === \App\Models\Lecturer::class
                                                ? ($official->employee->full_name_with_degree ?? $official->employee->user->name)
                                                : $official->employee->user->name }}
                                            <br>
                                            <small class="text-muted">{{ $official->employee_type === \App\Models\Lecturer::class ? 'Dosen' : 'Tendik' }}</small>
                                        @else
                                            <span class="text-danger">Data Pegawai Terhapus</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $official->start_date->format('d M Y') }} -
                                        {{ $official->end_date ? $official->end_date->format('d M Y') : 'Sekarang' }}
                                    </td>
                                    <td>{{ $official->sk_number ?? '-' }}</td>
                                    <td>
                                        @if($official->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button wire:click="edit({{ $official->id }})" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:confirm="Yakin ingin menghapus data ini?" wire:click="delete({{ $official->id }})" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data pejabat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
