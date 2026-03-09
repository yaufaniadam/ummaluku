<div>
    @section('title', 'Persetujuan KRS (Kaprodi)')
    @section('content_header')
        <h1>Persetujuan KRS (Kaprodi)</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa Menunggu Persetujuan Kaprodi</h3>
            <div class="card-tools">
                <input type="text" wire:model.live="search" class="form-control form-control-sm" placeholder="Cari Mahasiswa...">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->nim }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->program->name_id }}</td>
                            <td>
                                <span class="badge badge-info">Menunggu Kaprodi</span>
                            </td>
                            <td>
                                <a href="{{ route('prodi.krs-approval.detail', $student->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data mahasiswa yang menunggu persetujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $students->links() }}
        </div>
    </div>
</div>
