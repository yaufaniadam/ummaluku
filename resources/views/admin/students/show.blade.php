@extends('adminlte::page')
@section('title', 'Detail Mahasiswa')
@section('content_header')
    <h1 class="mb-1">Detail Mahasiswa</h1>
    <h5 class="font-weight-light">
        {{-- Breadcrumb bisa disesuaikan dari halaman mana user datang --}}
        <a href="{{ url()->previous() }}">Kembali</a> > {{ $student->user->name }}
    </h5>
@stop
@section('content')
    <div class="row">
        <div class="col-md-4">
            {{-- Card Profil Utama --}}
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-rounded-circle"
                            src="{{ $student->user->prospective->photo_path ? Storage::url($student->user->prospective->photo_path) : asset('assets/user.png') }}"
                            alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ $student->user->name }}</h3>
                    <p class="text-muted text-center">{{ $student->program->name_id }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NIM</b> <a class="float-right">{{ $student->nim }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Angkatan</b> <a class="float-right">{{ $student->entry_year }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> <a class="float-right"><span
                                    class="badge badge-success">{{ Str::title($student->status) }}</span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Dosen Wali Akademik</b> <a
                                class="float-right">{{ $student->academicAdvisor->full_name_with_degree ?? 'Belum Diatur' }}</a>
                        </li>
                    </ul>
                    @if (auth()->user()->hasRole(['Super Admin', 'Staf Akademik']))
                        <a href="{{ route('admin.akademik.students.edit', $student->id) }}"
                            class="btn btn-primary btn-block" wire:navigate><b>Edit Data</b></a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Card dengan Tab --}}
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#biodata" data-toggle="tab">Biodata</a></li>
                        <li class="nav-item"><a class="nav-link" href="#riwayat_krs" data-toggle="tab">Riwayat Studi
                                (KRS)</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="#transkrip" data-toggle="tab">Transkrip (Coming
                                Soon)</a></li> --}}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="biodata">
                            <strong><i class="fas fa-book mr-1"></i> Data Diri</strong>
                            <p class="text-muted">
                                NIK: {{ $student->user->prospective->id_number ?? '-' }} <br>
                                Tempat, Tanggal Lahir: {{ $student->user->prospective->birth_place ?? '-' }},
                                {{ $student->user->prospective->birth_date ? \Carbon\Carbon::parse($student->user->prospective->birth_date)->isoFormat('D MMMM YYYY') : '-' }}
                                <br>
                                Jenis Kelamin: {{ $student->user->prospective->gender ?? '-' }}<br>
                                Email: {{ $student->user->email ?? '-' }}
                            </p>
                            <hr>
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                            <p class="text-muted">{{ $student->user->prospective->address ?? 'Belum diisi' }}</p>
                            <hr>
                            <strong><i class="fas fa-users mr-1"></i> Data Orang Tua</strong>
                            <p class="text-muted">
                                Nama Ayah: {{ $student->user->prospective->father_name ?? '-' }} <br>
                                Nama Ibu: {{ $student->user->prospective->mother_name ?? '-' }}
                            </p>
                        </div>

                        {{-- ... di dalam file admin/students/show.blade.php --}}

                        <div class="tab-pane" id="riwayat_krs">
                            @if ($enrollmentsBySemester->isEmpty())
                                <div class="text-center text-muted">Belum ada riwayat studi yang bisa ditampilkan.</div>
                            @else
                                {{-- Loop untuk setiap semester --}}
                                @foreach ($enrollmentsBySemester as $semesterName => $enrollments)
                                    <div class="card card-outline card-info mb-4">
                                        <div class="card-header">
                                            <h3 class="card-title font-weight-bold">{{ $semesterName }}</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Kode MK</th>
                                                        <th>Nama Mata Kuliah</th>
                                                        <th>SKS</th>
                                                        <th class="text-center">Nilai Huruf</th>
                                                        <th class="text-center">Nilai Indeks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($enrollments as $enrollment)
                                                        <tr>
                                                            <td>{{ $enrollment->courseClass->course->code }}</td>
                                                            <td>{{ $enrollment->courseClass->course->name }}</td>
                                                            <td>{{ $enrollment->courseClass->course->sks }}</td>
                                                            <td class="text-center font-weight-bold">
                                                                {{ $enrollment->grade_letter ?? '-' }}</td>
                                                            <td class="text-center">
                                                                {{ $enrollment->grade_index ? number_format($enrollment->grade_index, 2) : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    @php
                                                        $totalSksSemester = $enrollments->sum('courseClass.course.sks');
                                                        $totalBobotSemester = $enrollments->sum(function ($e) {
                                                            return ($e->grade_index ?? 0) *
                                                                $e->courseClass->course->sks;
                                                        });
                                                        $ips =
                                                            $totalSksSemester > 0
                                                                ? $totalBobotSemester / $totalSksSemester
                                                                : 0;
                                                    @endphp
                                                    <tr class="bg-light">
                                                        <td colspan="2" class="text-right font-weight-bold">Indeks
                                                            Prestasi Semester (IPS)</td>
                                                        <td class="font-weight-bold">{{ $totalSksSemester }}</td>
                                                        <td colspan="2" class="text-center font-weight-bold">
                                                            {{ number_format($ips, 2) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Ringkasan IPK di bagian bawah --}}
                                <div class="mt-4">
                                    <h4>Total IP Kumulatif (IPK): <span
                                            class="text-primary font-weight-bold">{{ number_format($ipk, 2) }}</span></h4>
                                </div>

                            @endif
                        </div>

                        {{-- <div class="tab-pane" id="transkrip">
                            <p>Fitur Transkrip Nilai akan segera hadir.</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
