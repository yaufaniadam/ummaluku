<div>
    @section('title', 'Kelola Kaprodi: ' . $program->name_id)
    @section('content_header')
        <h1>Kelola Kaprodi: {{ $program->name_id }}</h1>
    @endsection

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tetapkan Kaprodi Baru</h3>
                </div>
                <form wire:submit.prevent="assignHead">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Dosen</label>
                            <select wire:model="lecturer_id" class="form-control @error('lecturer_id') is-invalid @enderror">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }}</option>
                                @endforeach
                            </select>
                            @error('lecturer_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Tanggal Mulai (SK)</label>
                            <input type="date" wire:model="start_date" class="form-control @error('start_date') is-invalid @enderror">
                            @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Nomor SK (Opsional)</label>
                            <input type="text" wire:model="sk_number" class="form-control @error('sk_number') is-invalid @enderror">
                            @error('sk_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan & Tetapkan</button>
                        <a href="{{ route('master.programs.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Kaprodi</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Pejabat</th>
                                <th>Mulai Menjabat</th>
                                <th>Selesai Menjabat</th>
                                <th>No SK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($heads as $head)
                                <tr class="{{ $head->is_active ? 'table-success' : '' }}">
                                    <td>{{ $head->lecturer->full_name_with_degree }}</td>
                                    <td>{{ $head->start_date->translatedFormat('d F Y') }}</td>
                                    <td>{{ $head->end_date ? $head->end_date->translatedFormat('d F Y') : '-' }}</td>
                                    <td>{{ $head->sk_number ?? '-' }}</td>
                                    <td>
                                        @if($head->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada riwayat Kaprodi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
