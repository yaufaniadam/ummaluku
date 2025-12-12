<div>
    @section('title', 'Manajemen Matakuliah (Prodi)')
    @section('content_header')
        <h1>Manajemen Matakuliah</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Matakuliah</h3>
            <div class="card-tools d-flex align-items-center">
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
                            <td>
                                @foreach($course->curriculums as $curriculum)
                                    <span class="badge badge-info">{{ $curriculum->name }}</span>
                                @endforeach
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
