@extends('adminlte::page')

@section('title', 'Kelola Kelas Perkuliahan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Kelola Kelas Perkuliahan</h1>
            <h5 class="font-weight-light">
                <a href="{{ route('admin.akademik.academic-years.index') }}" wire:navigate>Tahun Ajaran</a> >
                <a href="{{ route('admin.akademik.academic-years.show', $academicYear) }}"
                    wire:navigate>{{ $academicYear->name }}</a> >
                {{ $program->name_id }}
            </h5>
        </div>
        <div>
            {{-- TOMBOL TOOLS --}}
            <div class="btn-group">
                <button type="button" class="btn btn-tool btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-magic"></i> Fitur Bantu Admin
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    {{-- TOMBOL AUTO GENERATE --}}
                    <form
                        action="{{ route('admin.akademik.academic-years.programs.course-classes.autoGenerate', ['academic_year' => $academicYear, 'program' => $program]) }}"
                        method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin membuat kelas secara otomatis untuk semua mata kuliah yang belum memiliki kelas di semester ini?');">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-robot mr-2"></i> Auto-Generate Kelas
                        </button>
                    </form>

                    <div class="dropdown-divider"></div>

                    {{-- TOMBOL COPY CLASS --}}
                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#copyClassModal">
                        <i class="fas fa-copy mr-2"></i> Salin dari Tahun Lalu
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- CARD UNTUK MEMBUAT KELAS --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Mata Kuliah dari Kurikulum: {{ $activeCurriculum->name ?? 'Tidak Ada Kurikulum Aktif' }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th style="width: 300px;">Pilih Dosen Pengampu</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($availableCourses as $course)
                        <tr>
                            <td>
                                <strong>{{ $course->code }}</strong> - {{ $course->name }} ({{ $course->sks }} SKS) <br>
                                <small class="text-muted">Rekomendasi Semester:
                                    {{ $course->semester_recommendation }}</small>
                            </td>
                            <form
                                action="{{ route('admin.akademik.academic-years.programs.course-classes.quickCreate', ['academic_year' => $academicYear, 'program' => $program, 'course' => $course]) }}"
                                method="POST">
                                @csrf
                                <td>
                                    <select name="lecturer_id" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach ($lecturers as $lecturer)
                                            <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Buat Kelas
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Semua mata kuliah untuk prodi ini sudah dibuatkan kelas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CARD UNTUK KELAS YANG SUDAH JADI --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">Daftar Kelas yang Sudah Dibuat</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        {{-- <th>Kelas</th> --}}
                        <th>Dosen Pengampu</th>
                        {{-- <th>Kapasitas</th> --}}
                        {{-- <th>Jadwal</th> --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Lakukan perulangan untuk setiap grup semester --}}
                    @forelse ($createdClassesBySemester as $semester => $classes)
                        {{-- Tampilkan baris sub-judul semester --}}
                        <tr class="bg-light">
                            <td colspan="6" class="font-weight-bold">
                                @if ($semester > 0)
                                    SEMESTER {{ $semester }}
                                @else
                                    SEMESTER BELUM DIATUR
                                @endif
                            </td>
                        </tr>

                        {{-- Lakukan perulangan untuk setiap kelas di dalam grup semester --}}
                        @foreach ($classes as $class)
                            <tr>
                                <td>{{ $class->course->name }}</td>
                                {{-- <td>{{ $class->name }}</td> --}}
                                <td>{{ $class->lecturer?->full_name_with_degree ?? 'Belum ditentukan' }}</td>
                                {{-- <td>{{ $class->capacity }}</td> --}}
                                {{-- <td>{{ $class->day ?? '-' }},
                                {{ $class->start_time ? date('H:i', strtotime($class->start_time)) : '' }}</td> --}}
                                <td>
                                    <a href="{{ route('admin.akademik.academic-years.programs.course-classes.edit', ['academic_year' => $academicYear, 'program' => $program, 'course_class' => $class]) }}"
                                        class="btn btn-primary btn-xs" wire:navigate>Edit Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada kelas yang dibuat untuk semester ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL COPY CLASS --}}
    <div class="modal fade" id="copyClassModal" tabindex="-1" role="dialog" aria-labelledby="copyClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form
                    action="{{ route('admin.akademik.academic-years.programs.course-classes.copyFromPrevious', ['academic_year' => $academicYear, 'program' => $program]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="copyClassModalLabel">Salin Kelas dari Tahun Lalu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="source_academic_year_id">Pilih Tahun Ajaran Sumber</label>
                            <select name="source_academic_year_id" id="source_academic_year_id" class="form-control"
                                required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach ($previousAcademicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} ({{ $year->semester_type }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Sistem akan menyalin semua kelas dari tahun ajaran yang dipilih ke tahun ajaran ini
                                ({{ $academicYear->name }}).
                                <br>
                                <strong>Catatan:</strong> Kelas yang sudah ada (duplikat) tidak akan disalin ulang.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary"
                            onclick="if(confirm('Apakah Anda yakin?')){ this.form.submit(); }">Salin Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
