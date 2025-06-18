<div>
    <div class="page-header">
        <h2 class="page-title">Dashboard Pendaftaran</h2>
    </div>
    <div class="page-body">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Dokumen Persyaratan</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <p>Silakan unggah semua dokumen yang disyaratkan untuk <strong>{{ $application->admissionCategory->name }}</strong>.</p>
                
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Dokumen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requiredDocuments as $requirement)
                                @php
                                    $uploadedDocument = $application->documents->firstWhere('document_requirement_id', $requirement->id);
                                @endphp
                                <tr>
                                    <td>
                                        {{ $requirement->name }}
                                        <div class="text-muted">{{ $requirement->description }}</div>
                                    </td>
                                    <td>
                                        @if ($uploadedDocument)
                                            <span class="badge bg-{{ $uploadedDocument->status == 'verified' ? 'success' : 'info' }} me-1"></span>
                                            {{ Str::title(str_replace('_', ' ', $uploadedDocument->status)) }}
                                        @else
                                            <span class="badge bg-secondary me-1"></span> Belum Diunggah
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$uploadedDocument || $uploadedDocument->status == 'revision_needed')
                                            <div>
                                                <input type="file" class="form-control" wire:model="uploads.{{ $requirement->id }}">
                                                @error('uploads.'.$requirement->id) <span class="text-danger">{{ $message }}</span> @enderror
                                                
                                                <div wire:loading wire:target="uploads.{{ $requirement->id }}">Uploading...</div>
                                                
                                                <button class="btn btn-sm btn-primary mt-1" 
                                                        wire:click="uploadDocument({{ $requirement->id }})"
                                                        wire:loading.attr="disabled">
                                                    Upload
                                                </button>
                                            </div>
                                        @else
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Lihat File</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>