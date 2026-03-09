<div>
    {{-- Modal Trigger is handled in the parent view --}}

    <div wire:ignore.self class="modal fade" id="modal-generate-krs-massal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalLabel"><i class="fas fa-magic mr-2"></i> Generate KRS Paket Massal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> Fitur ini akan membuatkan KRS Paket secara otomatis untuk <strong>seluruh mahasiswa aktif</strong> sesuai filter di bawah ini.
                    </div>

                    @if($results)
                        <div class="alert alert-{{ $results['status'] == 'success' ? 'success' : 'warning' }} alert-dismissible fade show">
                            {{ $results['message'] }}
                            <button type="button" class="close" wire:click="$set('results', null)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Program Studi <span class="text-danger">*</span></label>
                        <select wire:model="selectedProgram" class="form-control @error('selectedProgram') is-invalid @enderror">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                            @endforeach
                        </select>
                        @error('selectedProgram') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Angkatan <span class="text-danger">*</span></label>
                        <select wire:model="selectedYear" class="form-control @error('selectedYear') is-invalid @enderror">
                            <option value="">-- Pilih Angkatan --</option>
                            @foreach($entryYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('selectedYear') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" wire:click="generate" wire:loading.attr="disabled">
                        <span wire:loading.remove>Mulai Proses</span>
                        <span wire:loading><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
