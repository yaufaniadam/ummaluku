<div>
    @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Mata Kuliah dari Master</h5>
                    <button type="button" class="close" wire:click="$set('showModal', false)">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Pilih mata kuliah yang ingin ditambahkan ke kurikulum <strong>{{ $curriculum->name ?? '' }}</strong>.</p>
                    
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">Pilih</th>
                                    <th>Kode MK</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>SKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allCourses as $course)
                                <tr>
                                    <td><input type="checkbox" wire:model.live="selectedCourses" value="{{ $course->id }}"></td>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->sks }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Semua mata kuliah master sudah ada di dalam kurikulum ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="addCoursesToCurriculum" @if(empty($selectedCourses)) disabled @endif>
                        Tambahkan {{ count($selectedCourses) }} Mata Kuliah
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>