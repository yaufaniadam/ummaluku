<div>
    @section('title', 'Manajemen Kelas (Prodi)')
    @section('content_header')
        <h1>Manajemen Kelas</h1>
    @endsection

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kelas Perkuliahan</h3>
            <div class="card-tools d-flex align-items-center">
                <!-- Action Buttons -->
                <div class="btn-group mr-2">
                    <a href="{{ route('prodi.course-class.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                    <button type="button" class="btn btn-success btn-sm"
                            wire:click="autoGenerate"
                            wire:confirm="Apakah Anda yakin ingin membuat kelas secara otomatis dari kurikulum aktif?">
                        <i class="fas fa-magic"></i> Auto Generate
                    </button>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#copyModal">
                        <i class="fas fa-copy"></i> Salin
                    </button>
                </div>

                <select wire:model.live="activeYearId" class="form-control form-control-sm mr-2" style="width: 150px;">
                    <option value="">Semua Tahun</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                            {{ $year->name }} ({{ $year->semester }})
                        </option>
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
                            <td colspan="7" class="text-center">Tidak ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $classes->links() }}
        </div>
    </div>

    <!-- Modal Copy Previous -->
    <div class="modal fade" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="copyModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="copyModalLabel">Salin Kelas dari Tahun Lalu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Pilih tahun ajaran sumber untuk menyalin data kelas ke tahun ajaran aktif saat ini.</p>
                    <div class="form-group">
                        <label>Tahun Ajaran Sumber</label>
                        <select class="form-control" id="sourceYearSelect">
                            @foreach($previousAcademicYears as $pyear)
                                <option value="{{ $pyear->id }}">{{ $pyear->name }} ({{ $pyear->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary"
                            onclick="let val = document.getElementById('sourceYearSelect').value; if(val) { @this.copyFromPrevious(val); $('#copyModal').modal('hide'); }">
                        Salin Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
