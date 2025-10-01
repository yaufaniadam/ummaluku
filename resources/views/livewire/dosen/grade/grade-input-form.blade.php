<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th style="width: 150px">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enrollments as $key => $enrollment)
                        <tr>
                            <td>{{ $key + 1 }}.</td>
                            <td>{{ $enrollment->student->nim }}</td>
                            <td>{{ $enrollment->student->user->name }}</td>
                            <td>
                                {{-- Dropdown untuk setiap mahasiswa, terhubung ke array $grades --}}
                                <select wire:model="grades.{{ $enrollment->id }}" class="form-control form-control-sm">
                                    <option value="">-- Belum Dinilai --</option>
                                    @foreach ($gradeOptions as $grade => $index)
                                        <option value="{{ $grade }}">{{ $grade }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada mahasiswa yang KRS-nya disetujui untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!$enrollments->isEmpty())
            <div class="card-footer">
                <button class="btn btn-primary" wire:click="saveGrades">
                    <span wire:loading wire:target="saveGrades" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Simpan Semua Nilai
                </button>
            </div>
        @endif
    </div>
</div>