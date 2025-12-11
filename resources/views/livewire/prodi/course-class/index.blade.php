<div>
    @section('title', 'Manajemen Kelas (Prodi)')
    @section('content_header')
        <h1>Manajemen Kelas</h1>
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kelas Perkuliahan</h3>
            <div class="card-tools d-flex align-items-center">
                <a href="{{ route('prodi.course-class.create') }}" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-plus"></i> Tambah
                </a>
                 <select wire:model.live="activeYearId" class="form-control form-control-sm mr-2" style="width: 150px;">
                    <option value="">Semua Tahun</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }} ({{ $year->semester }})</option>
                    @endforeach
                </select>
                <input type="text" wire:model.live="search" class="form-control form-control-sm" placeholder="Cari Kelas/MK..." style="width: 200px;">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Kode MK</th>
                        <th>Matakuliah</th>
                        <th>Kelas</th>
                        <th>Kapasitas</th>
                        <th>Dosen</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $class)
                        <tr>
                            <td>{{ $class->course->code }}</td>
                            <td>{{ $class->course->name }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->capacity }}</td>
                            <td>{{ $class->lecturer->full_name_with_degree ?? 'TBA' }}</td>
                            <td>
                                {{ $class->day ?? '-' }},
                                {{ $class->start_time ? \Carbon\Carbon::parse($class->start_time)->format('H:i') : '-' }} -
                                {{ $class->end_time ? \Carbon\Carbon::parse($class->end_time)->format('H:i') : '-' }}
                                ({{ $class->room ?? '-' }})
                            </td>
                            <td>
                                <a href="{{ route('prodi.course-class.edit', $class->id) }}" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $classes->links() }}
        </div>
    </div>
</div>
