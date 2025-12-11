<div>
    @section('title', 'Manajemen Matakuliah (Prodi)')
    @section('content_header')
        <h1>Manajemen Matakuliah</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Matakuliah</h3>
            <div class="card-tools d-flex align-items-center">
                <a href="{{ route('prodi.course.create') }}" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-plus"></i> Tambah
                </a>
                <input type="text" wire:model.live="search" class="form-control form-control-sm" placeholder="Cari Kode/Nama...">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Jenis</th>
                        <th>Kurikulum</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->sks }}</td>
                            <td>{{ $course->semester_recommendation }}</td>
                            <td>{{ $course->type }}</td>
                            <td>{{ $course->curriculum->name }}</td>
                            <td>
                                <a href="{{ route('prodi.course.edit', $course->id) }}" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data matakuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $courses->links() }}
        </div>
    </div>
</div>
