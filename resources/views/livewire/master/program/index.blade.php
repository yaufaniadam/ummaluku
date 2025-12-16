<div>
    @section('title', 'Master Program Studi')
    @section('content_header')
        <h1>Master Program Studi</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Program Studi</h3>
            <div class="card-tools">
                <input type="text" wire:model.live="search" class="form-control form-control-sm" placeholder="Cari Prodi...">
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

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="modal fade show" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Program Studi</h4>
                        <button type="button" class="close" wire:click="cancelEdit" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="editCode">Kode</label>
                                <input type="text" class="form-control @error('editCode') is-invalid @enderror" id="editCode" wire:model="editCode">
                                @error('editCode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="editNameId">Nama (ID)</label>
                                <input type="text" class="form-control @error('editNameId') is-invalid @enderror" id="editNameId" wire:model="editNameId">
                                @error('editNameId') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="editNameEn">Nama (EN)</label>
                                <input type="text" class="form-control @error('editNameEn') is-invalid @enderror" id="editNameEn" wire:model="editNameEn">
                                @error('editNameEn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="editDegree">Jenjang</label>
                                <select class="form-control @error('editDegree') is-invalid @enderror" id="editDegree" wire:model="editDegree">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                    <option value="Profesi">Profesi</option>
                                </select>
                                @error('editDegree') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="editFacultyId">Fakultas</label>
                                <select class="form-control @error('editFacultyId') is-invalid @enderror" id="editFacultyId" wire:model="editFacultyId">
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                                @error('editFacultyId') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" wire:click="cancelEdit">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
