<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Rencana Studi</h3>
        </div>
        <div class="card-body">
            <p><strong>Nama Mahasiswa:</strong> {{ $student->user->name }}</p>
            <p><strong>NIM:</strong> {{ $student->nim }}</p>
            <p><strong>Total SKS Diajukan:</strong> <span class="badge badge-primary">{{ $totalSks }} SKS</span></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kelas yang Diambil</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        {{-- <th>Kelas</th> --}}
                        <th>Dosen Pengampu</th>
                        {{-- <th>Jadwal</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->courseClass->course->code }}</td>
                            <td>{{ $enrollment->courseClass->course->name }}</td>
                            <td>{{ $enrollment->courseClass->course->sks }}</td>
                            {{-- <td>{{ $enrollment->courseClass->name }}</td> --}}
                            <td>{{ $enrollment->courseClass->lecturer?->full_name_with_degree ?? 'TBA' }}</td>
                            {{-- <td>{{ $enrollment->courseClass->day ?? '-' }}, {{ $enrollment->courseClass->start_time ? date('H:i', strtotime($enrollment->courseClass->start_time)) : '-' }}</td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data KRS yang menunggu persetujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($enrollments && !$enrollments->isEmpty())
            <div class="card-footer d-flex justify-content-end">
                <button class="btn btn-danger mr-2" wire:click="rejectKrs" wire:confirm="Apakah Anda yakin ingin menolak seluruh KRS ini?">
                    Tolak Seluruh KRS
                </button>
                <button class="btn btn-success" wire:click="approveKrs" wire:confirm="Apakah Anda yakin ingin menyetujui seluruh KRS ini?">
                    Setujui Seluruh KRS
                </button>
            </div>
        @endif
    </div>
</div>