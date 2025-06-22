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
                                <a href="{{ Storage::url($uploadedDocument->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-info">Lihat File</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($uploadedDocument)
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-success"
                                        wire:click="verifyDocument({{ $uploadedDocument->id }})"
                                        wire:confirm="Anda yakin ingin MENYETUJUI dokumen ini?">
                                        <i class="fas fa-check"></i> Terima
                                    </button>
                                    {{-- <button class="btn btn-sm btn-danger"
                                        wire:click="rejectDocument({{ $uploadedDocument->id }})"
                                        wire:confirm="Anda yakin ingin MENOLAK dokumen ini?">
                                        <i class="fas fa-times"></i>
                                    </button> --}}
                                    <button class="btn btn-sm btn-warning"
                                        onclick="promptForRevision({{ $uploadedDocument->id }})">
                                        <i class="fas fa-edit"></i> Revisi
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- JANGAN LUPA PINDAHKAN JUGA SCRIPT-NYA KE SINI --}}
    @push('js')
        <script>
            // Fungsi untuk memunculkan kotak input prompt bawaan browser
            function promptForRevision(documentId) {
                const notes = prompt('Silakan masukkan catatan revisi untuk dokumen ini:');

                // Kirim data ke Livewire HANYA JIKA user mengisi catatan dan mengklik OK
                if (notes != null && notes.trim() !== "") {
                    // Panggil method 'requireRevision' di komponen DocumentManager.php
                    // dengan membawa ID dokumen dan isi catatannya.
                    @this.call('requireRevision', documentId, notes);
                }
            }

            // Listener ini tetap berguna untuk notifikasi setelah aksi (Setujui/Tolak/Revisi)
            document.addEventListener('show-alert', event => {
                Swal.fire({
                    title: "Berhasil",
                    text: "Dokumen sudah diverifikasi",
                    type: "success",
                    confirmButtonText: 'Oke'
                });
            });
        </script>
    @endpush
</div>
