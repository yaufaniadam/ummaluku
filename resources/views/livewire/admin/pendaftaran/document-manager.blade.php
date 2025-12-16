<div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Nama Dokumen</th>
                    <th>Status</th>
                    <th>File Diunggah</th>
                    <th class="w-1 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($application->admissionCategory->documentRequirements as $requirement)
                    @php
                        $uploadedDocument = $application->documents->firstWhere(
                            'document_requirement_id',
                            $requirement->id,
                        );
                    @endphp
                    <tr>
                        <td>{{ $requirement->name }}</td>
                        <td>
                            @if ($uploadedDocument)
                                @if ($uploadedDocument->status == 'verified')
                                    <span class="badge bg-success me-1">Terverifikasi</span> 
                                @elseif($uploadedDocument->status == 'revision_needed')
                                    <span class="badge bg-warning me-1">Perlu Revisi</span> 
                                @elseif($uploadedDocument->status == 'rejected')
                                    <span class="badge bg-danger me-1"></span> Ditolak
                                @else
                                    <span class="badge bg-info me-1"></span> Menunggu Verifikasi
                                @endif
                            @else
                                <span class="badge bg-secondary me-1"></span> Belum Diunggah
                            @endif

                            {{-- BLOK BARU UNTUK MENAMPILKAN CATATAN REVISI --}}
                            @if ($uploadedDocument && $uploadedDocument->status == 'revision_needed' && !empty($uploadedDocument->notes))
                                <div class="text-danger mt-2" style="font-size: 0.8rem;">
                                    <i class="fas fa-fw fa-comment"></i>
                                    <strong></strong> {{ $uploadedDocument->notes }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($uploadedDocument)
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#documentModal-{{ $uploadedDocument->id }}">
                                    Verifikasi
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="documentModal-{{ $uploadedDocument->id }}" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel-{{ $uploadedDocument->id }}" aria-hidden="true" wire:ignore.self>
                                    <div class="modal-dialog modal-xl" role="document" style="max-width: 90vw;">
                                        <div class="modal-content" style="height: 90vh;">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="documentModalLabel-{{ $uploadedDocument->id }}">Verifikasi: {{ $requirement->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-0" style="height: calc(90vh - 120px);">
                                                @php
                                                    $fileUrl = Storage::url($uploadedDocument->file_path);
                                                    $extension = pathinfo($uploadedDocument->file_path, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                    $isPdf = strtolower($extension) === 'pdf';
                                                @endphp

                                                @if($isPdf)
                                                    <iframe src="{{ $fileUrl }}" style="width: 100%; height: 100%; border: none;"></iframe>
                                                @elseif($isImage)
                                                    <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                                                        <img src="{{ $fileUrl }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-center align-items-center h-100 flex-column">
                                                        <i class="fas fa-file fa-5x text-muted mb-3"></i>
                                                        <p>File type: {{ $extension }}</p>
                                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-primary">Unduh File</a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer" x-data>
                                                <button type="button" class="btn btn-success"
                                                    @click="
                                                        $('#documentModal-{{ $uploadedDocument->id }}').modal('hide');
                                                        Swal.fire({
                                                            title: 'Terima Dokumen?',
                                                            text: 'Pastikan dokumen sudah valid dan sesuai persyaratan.',
                                                            icon: 'question',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#28a745',
                                                            cancelButtonColor: '#6c757d',
                                                            confirmButtonText: 'Ya, Terima',
                                                            cancelButtonText: 'Batal'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                Swal.fire({
                                                                    title: 'Memproses...',
                                                                    html: 'Mohon tunggu sebentar.',
                                                                    allowOutsideClick: false,
                                                                    showConfirmButton: false,
                                                                    willOpen: () => Swal.showLoading()
                                                                });
                                                                $wire.verifyDocument({{ $uploadedDocument->id }});
                                                            } else {
                                                                $('#documentModal-{{ $uploadedDocument->id }}').modal('show');
                                                            }
                                                        })
                                                    ">
                                                    <i class="fas fa-check"></i> Terima
                                                </button>

                                                <button type="button" class="btn btn-warning"
                                                    @click="
                                                        $('#documentModal-{{ $uploadedDocument->id }}').modal('hide');
                                                        Swal.fire({
                                                            title: 'Minta Revisi Dokumen',
                                                            input: 'textarea',
                                                            inputLabel: 'Catatan Revisi',
                                                            inputPlaceholder: 'Tuliskan alasan mengapa dokumen ini perlu direvisi...',
                                                            inputValidator: (value) => {
                                                                if (!value) { return 'Catatan revisi wajib diisi!' }
                                                            },
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Kirim Permintaan Revisi',
                                                            confirmButtonColor: '#ffc107',
                                                            cancelButtonText: 'Batal'
                                                        }).then((result) => {
                                                            if (result.isConfirmed && result.value) {
                                                                Swal.fire({
                                                                    title: 'Mengirim...',
                                                                    html: 'Mohon tunggu sebentar.',
                                                                    allowOutsideClick: false,
                                                                    showConfirmButton: false,
                                                                    willOpen: () => Swal.showLoading()
                                                                });
                                                                $wire.requireRevision({{ $uploadedDocument->id }}, result.value);
                                                            } else {
                                                                $('#documentModal-{{ $uploadedDocument->id }}').modal('show');
                                                            }
                                                        })
                                                    ">
                                                    <i class="fas fa-edit"></i> Minta Revisi
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                           -
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- JANGAN LUPA PINDAHKAN JUGA SCRIPT-NYA KE SINI --}}
    @push('js')
        <!-- Ensure SweetAlert2 v11 is loaded -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Listener untuk menutup loading Swal setelah proses selesai
            document.addEventListener('document-status-updated', event => {
                // Tutup loading
                Swal.close();
            });

             // Listener global show-alert yang mungkin dipanggil oleh backend
            document.addEventListener('show-alert', event => {
                // Jika event.detail berbentuk array (dari dispatch Livewire), kita ambil detailnya
                let data = event.detail;
                // Kadang Livewire membungkus dalam array [0] jika dispatch multiple params
                if (Array.isArray(data) && data.length > 0) {
                     data = data[0]; // Ambil object pertamanya
                } elseif (Array.isArray(data)) {
                     // Fallback jika kosong atau struktur lain
                     data = { type: 'success', message: 'Operasi berhasil' };
                }

                Swal.fire({
                    title: data.type === 'error' ? 'Gagal' : (data.type === 'warning' ? 'Perhatian' : 'Berhasil'),
                    text: data.message,
                    icon: data.type || 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endpush
</div>
