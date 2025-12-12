<div>
    @section('title', 'Detail Persetujuan KRS (Kaprodi)')
    @section('content_header')
        <h1>Detail Persetujuan KRS: {{ $student->user->name }}</h1>
    @endsection

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $student->user->name }}</h3>
                    <p class="text-muted text-center">{{ $student->nim }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Prodi</b> <a class="float-right">{{ $student->program->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Semester</b> <a class="float-right">{{ $student->semester }}</a>
                        </li>
                         <li class="list-group-item">
                            <b>Total SKS Diambil</b> <a class="float-right">{{ $totalSks }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mata Kuliah yang Diajukan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Kelas</th>
                                <th>Dosen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->courseClass->course->code }}</td>
                                    <td>{{ $enrollment->courseClass->course->name }}</td>
                                    <td>{{ $enrollment->courseClass->course->sks }}</td>
                                    <td>{{ $enrollment->courseClass->name }}</td>
                                    <td>{{ $enrollment->courseClass->lecturer->full_name_with_degree ?? 'TBA' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada mata kuliah yang menunggu persetujuan Kaprodi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @if ($enrollments->isNotEmpty())
                        <button wire:click="approveKrs" class="btn btn-success float-right ml-2" wire:confirm="Apakah Anda yakin ingin menyetujui KRS ini?">
                            <i class="fas fa-check"></i> Setujui Semua
                        </button>
                        <button wire:click="rejectKrs" class="btn btn-danger float-right" wire:confirm="Apakah Anda yakin ingin menolak KRS ini?">
                            <i class="fas fa-times"></i> Tolak Semua
                        </button>
                    @endif
                    <a href="{{ route('prodi.krs-approval.index') }}" class="btn btn-default">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
