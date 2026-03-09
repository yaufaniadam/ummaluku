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

                        {{-- Countdown Timer --}}
                        @if($activeSemester->krs_end_date)
                        <div x-data="{
                            endTime: {{ \Carbon\Carbon::parse($activeSemester->krs_end_date)->endOfDay()->timestamp * 1000 }},
                            now: new Date().getTime(),
                            timeLeft: 0,
                            days: 0,
                            hours: 0,
                            minutes: 0,
                            seconds: 0,
                            update() {
                                this.now = new Date().getTime();
                                this.timeLeft = this.endTime - this.now;
                                if (this.timeLeft > 0) {
                                     this.days = Math.floor(this.timeLeft / (1000 * 60 * 60 * 24));
                                     this.hours = Math.floor((this.timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                     this.minutes = Math.floor((this.timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                     this.seconds = Math.floor((this.timeLeft % (1000 * 60)) / 1000);
                                } else {
                                     this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                                }
                            }
                        }" x-init="update(); setInterval(() => update(), 1000)" class="mt-3 p-2 bg-light rounded border">
                            <strong class="d-block text-danger mb-1"><i class="fas fa-hourglass-half"></i> Sisa Waktu Pengisian KRS:</strong>
                            <span class="h5 font-weight-bold" x-text="days"></span> Hari
                            <span class="h5 font-weight-bold" x-text="hours"></span> Jam
                            <span class="h5 font-weight-bold" x-text="minutes"></span> Menit
                            <span class="h5 font-weight-bold" x-text="seconds"></span> Detik
                        </div>
                        @endif
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
                                @forelse ($groupedAvailableClasses as $semester => $classes)
                                    <tr class="bg-secondary">
                                        <th colspan="5" class="text-center">Semester {{ $semester }}</th>
                                    </tr>
                                    @foreach ($classes as $class)
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
                                    @endforeach
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
                                    <th>Sem</th>
                                    <th>SKS</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedClasses as $classId => $class)
                                    <tr>
                                        <td>{{ $class['course']['name'] }} ({{$class['name']}})</td>
                                        <td>
                                            @if(isset($class['course']['curriculums']) && count($class['course']['curriculums']) > 0)
                                                {{ $class['course']['curriculums'][0]['pivot']['semester'] }}
                                            @else
                                                -
                                            @endif
                                        </td>
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
                                    <tr><td colspan="4" class="text-center">Anda belum memilih kelas.</td></tr>
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
