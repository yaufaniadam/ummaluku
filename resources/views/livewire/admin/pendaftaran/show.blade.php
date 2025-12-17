<div>
    @section('title', 'Detail Pendaftar')

    @section('content_header')
        <h1>Detail Pendaftar</h1>
        <div class="text-muted mt-1">{{ $application->registration_number }}</div>
    @endsection

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="row">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Biodata Lengkap</h3>
                </div>
                <div class="card-body">
                    {{-- Menggunakan ?-> untuk mengakses relasi yang mungkin null --}}
                    <table class="table table-striped">
                        <tr>
                            <td>Nama Lengkap:</td>
                            {{-- Rantai relasi: application -> prospective -> user -> name --}}
                            <td width="70%">{{ $application->prospective?->user?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin:</td>
                            <td width="70%">{{ $application->prospective?->gender ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir:</td>
                            <td width="70%">
                                {{-- Cek dulu apakah tanggal lahir ada sebelum memformatnya --}}
                                {{ $application->prospective?->birth_place ?? '' }}
                                @if ($application->prospective?->birth_date)
                                    ,
                                    {{ \Carbon\Carbon::parse($application->prospective->birth_date)->format('d F Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Agama:</td>
                            <td width="70%">{{ $application->prospective?->religion?->name ?? '-' }}</td>

                        </tr>
                        <tr>

                            <td>Kewarganegaraan:</td>
                            <td width="70%">{{ $application->prospective?->citizenship ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NIK:</td>
                            <td width="70%">{{ $application->prospective?->id_number ?? '-' }}</td>

                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td width="70%">{{ $application->prospective?->user?->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>No. Telepon:</td>
                            <td width="70%">{{ $application->prospective?->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat:</td>
                            <td width="70%">
                                {{ $application->prospective?->address ?? '-' }}<br>
                                {{-- Gunakan ?? '' agar tidak error jika relasi null di dalam fungsi --}}
                                Desa {{ ucwords(strtolower($application->prospective?->village?->name ?? '')) }},
                                Kecamatan {{ ucwords(strtolower($application->prospective?->district?->name ?? '')) }},
                                {{ ucwords(strtolower($application->prospective?->city?->name ?? '')) }},
                                {{ ucwords(strtolower($application->prospective?->province?->name ?? '')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Asal Sekolah:</td>
                            <td width="70%">{{ $application->prospective?->highSchool?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NISN:</td>
                            <td width="70%">{{ $application->prospective?->nisn ?? '-' }}</td>
                        </tr>
                        <tr>

                        </tr>
                        <tr>
                            <td>Penerima KPS:</td>
                            {{-- Memberikan nilai default `false` untuk pengecekan boolean --}}
                            <td width="70%">
                                {{ $application->prospective?->is_kps_recipient ?? false ? 'Ya' : 'Tidak' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Verifikasi Dokumen Persyaratan</h3>
                </div>
                <div class="card-body">
                    {{-- Komponen Livewire tidak perlu diubah --}}
                    @livewire('admin.pendaftaran.document-manager', ['application' => $application], key($application->id))
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Data Orang Tua / Wali</h3>
                </div>
                <div class="card-body">

                    <table class="table table-striped">
                        <tr>
                            <td>Nama Ayah:</td>
                            <td width="70%">{{ $application->prospective?->father_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan Ayah:</td>
                            <td width="70%">{{ $application->prospective?->father_occupation ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Penghasilan Ayah:</td>
                            {{-- Cek dulu datanya sebelum diformat, untuk menghindari menampilkan "Rp0" --}}
                            <td width="70%">
                                @if ($application->prospective?->father_income)
                                    Rp{{ number_format($application->prospective->father_income) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Ibu:</td>
                            <td width="70%">{{ $application->prospective?->mother_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan Ibu:</td>
                            <td width="70%">{{ $application->prospective?->mother_occupation ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Penghasilan Ibu:</td>
                            <td width="70%">
                                @if ($application->prospective?->mother_income)
                                    Rp{{ number_format($application->prospective->mother_income) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>No. Telepon Orang Tua:</td>
                            <td width="70%">{{ $application->prospective?->parent_phone ?? '-' }}</td>
                        </tr>
                    </table>

                    {{-- Pengecekan data wali sudah aman karena ada di dalam @if --}}
                    {{-- Namun kita tetap tambahkan ?-> untuk keamanan ekstra --}}
                    @if ($application->prospective?->with_guardian)
                        <hr>
                        <h5>Data Wali</h5>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>Nama Wali:</td>
                                <td width="70%">{{ $application->prospective?->guardian_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>No. Telepon Wali:</td>
                                <td width="70%">{{ $application->prospective?->guardian_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Pekerjaan Wali:</td>
                                <td width="70%">{{ $application->prospective?->guardian_occupation ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Pendapatan Wali:</td>
                                <td width="70%">
                                    @if ($application->prospective?->guardian_income)
                                        Rp{{ number_format($application->prospective->guardian_income) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Tindakan</h3>
                </div>
                <div class="card-body p-3">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.pmb.pendaftaran.index') }}"
                            class="btn btn-outline-primary rounded-0">Kembali</a>
                        @if ($application->status == 'proses_verifikasi')
                            {{-- Hanya tampilkan tombol jika tidak ada dokumen pending --}}
                            @if (!$this->hasPendingDocuments)
                                <button class="btn btn-outline-success rounded-0" wire:click="finalizeVerification"
                                    wire:confirm="Anda yakin semua dokumen sudah diperiksa dan pendaftar ini lolos ke tahap seleksi?">
                                    Loloskan Verifikasi</button>

                                <button class="btn btn-outline-danger rounded-0"
                                    onclick="promptForApplicationRejection()">
                                    Tolak Pendaftaran
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Pendaftaran</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <td>Status:</td>
                            <td width="70%"><span class="badge bg-warning me-1"></span>
                                {{ Str::title(str_replace('_', ' ', $application->status)) }}</td>
                        </tr>
                        <tr>
                            <td>Jalur:</td>
                            <td width="70%">{{ $application->admissionCategory?->name ?? 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <td>Gelombang:</td>
                            <td width="70%">{{ $application->batch?->name ?? '-' }}
                                ({{ $application->batch?->year ?? '-' }})</td>
                        </tr>
                        <tr>
                            <td>Tanggal Daftar:</td>
                            <td width="70%">{{ $application->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                    <hr>
                    <h4 class="mb-3">Pilihan Program Studi</h4>
                    @forelse ($application->programChoices as $choice)
                        <p>
                            <strong>Pilihan {{ $choice->choice_order }}:</strong><br>
                            {{ $choice->program?->name_id ?? 'Program studi tidak ditemukan' }}
                            ({{ $choice->program?->degree ?? '-' }})
                        </p>
                    @empty
                        <p class="text-muted">Tidak ada pilihan program studi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // ... fungsi promptForRevision dan listener show-alert sudah ada ...

            // Fungsi baru untuk memunculkan prompt penolakan aplikasi
            function promptForApplicationRejection() {
                Swal.fire({
                    title: 'Tolak Pendaftaran Ini?',
                    input: 'textarea',
                    inputPlaceholder: 'Tuliskan alasan penolakan di sini (wajib diisi)...',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Anda harus mengisi alasan penolakan!'
                        }
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak Pendaftaran',
                    confirmButtonColor: '#dc3545',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        // Panggil method Livewire dengan alasan dari SweetAlert
                        @this.call('rejectApplication', result.value);
                    }
                });
            }
        </script>
    @endpush
</div>
