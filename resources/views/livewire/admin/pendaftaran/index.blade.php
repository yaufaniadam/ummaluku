<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Pendaftar Masuk
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Aplikasi</h3>
                    <div class="ms-auto me-3">
                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Cari nama pendaftar...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>No. Reg</th>
                                <th>Nama Pendaftar</th>
                                <th>Email</th>
                                <th>Jalur</th>
                                <th>Gelombang</th>
                                <th>Tgl. Daftar</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($applications as $application)
                                <tr>
                                    <td><span class="text-muted">{{ $application->registration_number }}</span></td>
                                    <td>{{ $application->prospective->user->name }}</td>
                                    <td>{{ $application->prospective->user->email }}</td>
                                    <td>{{ $application->admissionCategory->name }}</td>
                                    <td>{{ $application->batch->name }}</td>
                                    <td>{{ $application->created_at->format('d M Y') }}</td>
                                    <td><span class="badge bg-warning me-1"></span> {{ Str::title(str_replace('_', ' ', $application->status)) }}</td>
                                    <td class="text-end">
                                       <a href="{{ route('admin.pendaftaran.show', $application) }}" class="btn btn-sm">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Tidak ada data ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>