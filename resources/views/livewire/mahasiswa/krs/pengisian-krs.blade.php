<div>
    @if (!$activeSemester)
        <div class="alert alert-warning">
            Saat ini periode pengisian KRS belum dibuka.
        </div>
    @else
        {{-- ====================== PANEL STATUS BARU ====================== --}}
        <div class="alert alert-{{ $krsStatusClass }}">
            <h5 class="alert-heading font-weight-bold">Status KRS Anda:
                {{ Str::title(str_replace('_', ' ', $krsStatus)) }}</h5>
            <p>{{ $krsStatusMessage }}</p>
        </div>
        {{-- Bagian Informasi Mahasiswa --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rencana Studi Semester: {{ $activeSemester->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $student->user->name }}</p>
                        <p><strong>NIM:</strong> {{ $student->nim }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Batas SKS:</strong> {{ $sksLimit }} SKS</p>
                        <p class="font-weight-bold"><strong>Total SKS Diambil:</strong> <span
                                class="badge badge-primary">{{ $totalSks }} SKS</span></p>
                        {{-- TAMBAHKAN BARIS DI BAWAH INI --}}
                        <p class="font-weight-bold"><strong>Estimasi Biaya Semester:</strong> <span
                                class="badge badge-success">Rp {{ number_format($estimatedFee, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- Bagian Kiri: Daftar Kelas Tersedia --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Pilihan Kelas Tersedia</h3></div>
                    <div class="card-body table-responsive p-0" style="height: 400px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Dosen</th>
                                    <th>Jadwal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($availableClasses as $class)
                                    <tr>
                                        <td>
                                            {{-- Tambahkan disabled jika KRS dikunci --}}
                                            <button class="btn btn-xs btn-success" wire:click="selectClass({{ $class->id }})" wire:loading.attr="disabled"
                                                @if(isset($selectedClasses[$class->id]) || $isKrsLocked) disabled @endif>
                                                Pilih
                                            </button>
                                        </td>
                                        <td>{{ $class->course->name }} ({{$class->name}})</td>
                                        <td>{{ $class->course->sks }}</td>
                                        <td>{{ $class->lecturer?->full_name_with_degree ?? 'TBA' }}</td>
                                        <td>{{ $class->day ?? '-' }}, {{ $class->start_time ? date('H:i', strtotime($class->start_time)) : '-' }} - {{ $class->end_time ? date('H:i', strtotime($class->end_time)) : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Tidak ada kelas yang ditawarkan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Bagian Kanan: Daftar Kelas yang Dipilih --}}
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Kelas yang Anda Pilih</h3></div>
                    <div class="card-body table-responsive p-0" style="height: 400px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedClasses as $classId => $class)
                                    <tr>
                                        <td>{{ $class['course']['name'] }} ({{$class['name']}})</td>
                                        <td>{{ $class['course']['sks'] }}</td>
                                        <td>
                                            {{-- Tambahkan disabled jika KRS dikunci --}}
                                            <button class="btn btn-xs btn-danger" wire:click="removeClass({{ $classId }})" wire:loading.attr="disabled"
                                                @if($isKrsLocked) disabled @endif>
                                                Batal
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">Anda belum memilih kelas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        {{-- Tambahkan disabled jika KRS dikunci --}}
                        <button class="btn btn-primary" wire:click="saveKrs" wire:loading.attr="disabled"
                            @if($isKrsLocked) disabled @endif>
                            <span wire:loading wire:target="saveKrs" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            @if($krsStatus === 'pending' || $krsStatus === 'rejected') Ajukan Ulang KRS @else Ajukan KRS @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
