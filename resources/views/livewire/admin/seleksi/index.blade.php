<div>
    @section('title', 'Proses Seleksi Pendaftar')

    @section('content_header')
        <h1>Proses Seleksi Pendaftar</h1>
    @stop

    @section('content')
        <p>Halaman ini berisi daftar calon mahasiswa yang telah lolos verifikasi dokumen dan siap untuk proses seleksi akhir.</p>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pendaftar Siap Seleksi</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>No. Registrasi</th>
                            <th>Nama Pendaftar</th>
                            <th>Pilihan Prodi</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td>{{ $loop->iteration + $applications->firstItem() - 1 }}.</td>
                                <td>{{ $application->registration_number }}</td>
                                <td>{{ $application->prospective->user->name }}</td>
                                <td>
                                    @foreach($application->programChoices as $choice)
                                        <div>Pilihan {{ $choice->choice_order }}: {{ $choice->program->name_id }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" 
                                                wire:click="acceptApplicant({{ $application->id }})"
                                                wire:confirm="Anda yakin ingin MENERIMA pendaftar ini?">
                                            Terima
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                                wire:click="rejectApplicant({{ $application->id }})"
                                                wire:confirm="Anda yakin ingin MENOLAK pendaftar ini?">
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    Tidak ada pendaftar yang siap untuk diseleksi saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $applications->links() }}
            </div>
        </div>
        
        {{-- Kita tambahkan listener untuk SweetAlert di sini juga --}}
       
        <script>
            document.addEventListener('show-alert', event => {
                Swal.fire({
                    title: event.detail.type === 'success' ? 'Berhasil!' : 'Info',
                    text: event.detail.message,
                    icon: event.detail.type,
                    confirmButtonText: 'Oke'
                });
            });
        </script>

    @stop
</div>