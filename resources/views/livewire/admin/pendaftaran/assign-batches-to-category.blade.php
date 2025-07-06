<div>
 
    <button wire:click="openModal" class="btn btn-xs btn-default text-warning">
        Atur Gelombang
    </button>


    <div class="modal fade" id="assignModal-{{ $category->id }}" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atur Gelombang untuk: {{ $category->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Pilih gelombang pendaftaran yang akan dibuka untuk jalur ini.</p>
                    <div class="row">
                        @if(empty($batches))
                            <p class="text-muted">Loading...</p>
                        @else
                            @foreach($batches as $batch)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $batch->id }}" 
                                           wire:model="attachedBatchIds" id="batch-{{ $category->id }}-{{ $batch->id }}">
                                    <label class="form-check-label" for="batch-{{ $category->id }}-{{ $batch->id }}">
                                        {{ $batch->name }} ({{ $batch->year }})<br><em class="text-muted">{{ $batch->description }}</em>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="save" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</div>