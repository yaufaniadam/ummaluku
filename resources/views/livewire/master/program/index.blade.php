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
                        <th>Kaprodi Saat Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programs as $program)
                        <tr>
                            <td>{{ $program->code }}</td>
                            <td>{{ $program->name_id }}</td>
                            <td>{{ $program->degree }}</td>
                            <td>{{ $program->faculty->name ?? '-' }}</td>
                            <td>
                                @if($program->currentHead && $program->currentHead->lecturer)
                                    {{ $program->currentHead->lecturer->full_name_with_degree }}
                                @else
                                    <span class="text-muted text-sm">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('master.programs.manage-head', $program->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-user-tie"></i> Kelola Kaprodi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data program studi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $programs->links() }}
        </div>
    </div>
</div>
