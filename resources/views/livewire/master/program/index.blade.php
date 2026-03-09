<div>
    @section('title', 'Master Program Studi')
    @section('content_header')
        <h1>Master Program Studi</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Program Studi</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" wire:model.live="search" class="form-control float-right" placeholder="Cari Prodi...">
                    <div class="input-group-append">
                        <button wire:click="create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Prodi</th>
                        <th>Jenjang</th>
                        <th>Fakultas</th>
                        <th>Kaprodi</th>
                        <th>Sekretaris</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programs as $program)
                        <tr>
                            <td>{{ $program->code }}</td>
                            <td>{{ $program->name_id }}</td>
                            <td>{{ $program->degree }}</td>
                            <td>{{ $program->faculty->name_id ?? '-' }}</td>
                            <td>
                                @if($program->currentHead && $program->currentHead->lecturer)
                                    {{ $program->currentHead->lecturer->full_name_with_degree }}
                                @else
                                    <span class="text-muted text-sm text-italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                @if($program->currentSecretary && $program->currentSecretary->lecturer)
                                    {{ $program->currentSecretary->lecturer->full_name_with_degree }}
                                @else
                                    <span class="text-muted text-sm text-italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                <button wire:click="edit({{ $program->id }})" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="{{ route('master.programs.manage-head', $program->id) }}" class="btn btn-info btn-sm" title="Kelola Pejabat">
                                    <i class="fas fa-user-tie"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data program studi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $programs->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    @if($showModal)
        <div class="modal fade show" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $isEdit ? 'Edit Program Studi' : 'Tambah Program Studi' }}</h4>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="code">Kode</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" wire:model="code">
                                @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="nameId">Nama (ID)</label>
                                <input type="text" class="form-control @error('nameId') is-invalid @enderror" id="nameId" wire:model="nameId">
                                @error('nameId') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="nameEn">Nama (EN)</label>
                                <input type="text" class="form-control @error('nameEn') is-invalid @enderror" id="nameEn" wire:model="nameEn">
                                @error('nameEn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="degree">Jenjang</label>
                                <select class="form-control @error('degree') is-invalid @enderror" id="degree" wire:model="degree">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                    <option value="Profesi">Profesi</option>
                                </select>
                                @error('degree') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="facultyId">Fakultas</label>
                                <select class="form-control @error('facultyId') is-invalid @enderror" id="facultyId" wire:model="facultyId">
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->name_id }}</option>
                                    @endforeach
                                </select>
                                @error('facultyId') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <script>
        window.addEventListener('close-modal', event => {
           // Logic handled by Livewire if using boolean flag
        })
    </script>
</div>
