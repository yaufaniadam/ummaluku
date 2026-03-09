
<div>
  <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Verifikasi Dokumen Persyaratan</h3>
                </div>
                <div class="card-body">
                    @livewire('admin.pendaftaran.document-manager', ['application' => $application], key($application->id))
                </div>
            </div>
</div>