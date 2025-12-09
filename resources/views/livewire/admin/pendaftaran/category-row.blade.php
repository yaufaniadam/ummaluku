<tr>
    <td>{{ $category->name }}</td>
    <td><span class="badge badge-secondary">{{ $category->display_group }}</span></td>
    <td>{!! $category->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
    <td>
        {{-- Tampilkan nama gelombang yang terhubung --}}
        <div class="d-flex flex-wrap" style="gap: 5px;">
            @php
                $activeBatches = $category->batches->where('is_active', true)->sortByDesc('year');
            @endphp

            @forelse($activeBatches as $batch)
                <span class="badge badge-success"
                    title="{{ $batch->start_date ? $batch->start_date->format('d M') : '' }} - {{ $batch->end_date ? $batch->end_date->format('d M Y') : '' }}">
                    {{ $batch->name }} {{ $batch->year }}
                    <i class="fas fa-check-circle ml-1" style="font-size: 0.8em;"></i>
                </span>
            @empty
                @if($category->batches->count() > 0)
                    <span class="text-muted text-xs"><i>Tidak ada gelombang aktif</i></span>
                @else
                    <span class="badge badge-light">Belum diatur</span>
                @endif
            @endforelse
        </div>
    </td>
    <td>
        <div class="btn-group">
            {{-- Tombol Edit standar --}}
            <a href="{{ route('admin.pmb.jalur-pendaftaran.edit', $category) }}" class="btn btn-xs btn-default text-primary">Edit</a>

            {{-- Tombol Atur Gelombang --}}
            <button wire:click="openModal" class="btn btn-xs btn-default text-warning">
                Atur Gelombang
            </button>
        </div>

        {{-- Modal --}}
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
    </td>
</tr>
