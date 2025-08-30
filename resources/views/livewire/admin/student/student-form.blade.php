<div>
    <form wire:submit="update">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulir Edit Mahasiswa</h3>
            </div>
            <div class="card-body">
                {{-- Informasi Read-Only --}}
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Mahasiswa:</strong><br>{{ $student->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>NIM:</strong><br>{{ $student->nim }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Program Studi:</strong><br>{{ $student->program->name_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Angkatan:</strong><br>{{ $student->entry_year }}</p>
                    </div>
                </div>
                <hr>

                {{-- Input yang Bisa Diedit --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="academic_advisor_id">Dosen Pembimbing Akademik</label>
                            <select wire:model="academic_advisor_id" id="academic_advisor_id" class="form-control @error('academic_advisor_id') is-invalid @enderror">
                                <option value="">-- Belum Diatur --</option>
                                @foreach ($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }}</option>
                                @endforeach
                            </select>
                            @error('academic_advisor_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status Mahasiswa</label>
                            <select wire:model="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="active">Aktif</option>
                                <option value="on_leave">Cuti</option>
                                <option value="graduated">Lulus</option>
                                <option value="dropped_out">Drop Out</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>