<div>
    @section('title', 'Kelola Pejabat Fakultas')
    @section('content_header')
        <h1>Kelola Pejabat: {{ $faculty->name_id }}</h1>
    @endsection

    <div class="row">
        <!-- Form Section -->
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tetapkan Pejabat Baru</h3>
                </div>
                <form wire:submit.prevent="assignOfficial">
                    <div class="card-body">
                        <!-- Position Selection -->
                        <div class="form-group">
                            <label>Jabatan</label>
                            <select wire:model.live="position" class="form-control">
                                <option value="Dekan">Dekan</option>
                                <option value="Kepala Tata Usaha">Kepala Tata Usaha (KTU)</option>
                                <option value="Kepala Bagian Administrasi Akademik">Kepala Bagian Administrasi Akademik</option>
                            </select>
                            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Employee Selection -->
                        <div class="form-group">
                            <label>
                                @if($position === 'Dekan')
                                    Pilih Dosen
                                @else
                                    Pilih Tenaga Kependidikan (Staff)
                                @endif
                            </label>

                            <select wire:model="employee_id" class="form-control">
                                <option value="">-- Pilih --</option>
                                @if($position === 'Dekan')
                                    @foreach($available_lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }} ({{ $lecturer->nidn }})</option>
                                    @endforeach
                                @else
                                    @foreach($available_staff as $staff)
                                        <option value="{{ $staff->id }}">
                                            {{ $staff->user->name ?? '-' }} ({{ $staff->nip }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Date & SK -->
                        <div class="form-group">
                            <label>Tanggal Mulai Menjabat</label>
                            <input type="date" wire:model="start_date" class="form-control">
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Nomor SK</label>
                            <input type="text" wire:model="sk_number" class="form-control" placeholder="Contoh: SK-123/2025">
                            @error('sk_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <a href="{{ route('master.faculties.index') }}" class="btn btn-default btn-block mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Fakultas
            </a>
        </div>

        <!-- History Section -->
        <div class="col-md-8">
            <!-- Dekan History -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Dekan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>SK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deans as $official)
                                <tr>
                                    <td>
                                        {{ $official->employee->full_name_with_degree ?? $official->employee->user->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $official->start_date->format('d M Y') }}</td>
                                    <td>{{ $official->end_date ? $official->end_date->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($official->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $official->sk_number ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KTU History -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Kepala Tata Usaha (KTU)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>SK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ktus as $official)
                                <tr>
                                    <td>
                                        {{ $official->employee->full_name_with_degree ?? $official->employee->user->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $official->start_date->format('d M Y') }}</td>
                                    <td>{{ $official->end_date ? $official->end_date->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($official->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $official->sk_number ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kabag History -->
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Kabag Adm. Akademik</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>SK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kabags as $official)
                                <tr>
                                    <td>
                                        {{ $official->employee->full_name_with_degree ?? $official->employee->user->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $official->start_date->format('d M Y') }}</td>
                                    <td>{{ $official->end_date ? $official->end_date->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($official->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $official->sk_number ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        window.addEventListener('alert', event => {
            toastr[event.detail.type](event.detail.message);
        });
    </script>
    @endpush
</div>
