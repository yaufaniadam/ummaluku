<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pilih Mata Kuliah dari Master</h3>
    </div>
    <div class="card-body">
        {{-- Filter --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <select wire:model.live="programId" class="form-control">
                    <option value="">Semua Program Studi</option>
                    <option value="universitas">Tingkat Universitas</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name_id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 ml-auto">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                    placeholder="Cari Kode atau Nama MK...">
            </div>
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px;">
                            <input type="checkbox" wire:model.live="selectAllOnPage" title="Pilih Semua di Halaman Ini">
                        </th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Prodi</th>
                        <th>Semester Penempatan</th>
                        <th>Pilihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>
                                <input type="checkbox" wire:model.live="selectedCourses.{{ $course->id }}"
                                    value="{{ $course->id }}">
                            </td>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->sks }}</td>
                            <td>{{ $course->program->name_id ?? 'Universitas' }}</td>
                            <td>
                                <input type="number" wire:model="courseData.{{ $course->id }}.semester"
                                    class="form-control form-control-sm" min="1" max="8">
                            </td>
                            <td>
                                <select wire:model="courseData.{{ $course->id }}.type"
                                    class="form-control form-control-sm">
                                    <option value="Wajib">Wajib</option>
                                    <option value="Pilihan">Pilihan</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $courses->links('vendor.livewire.bootstrap') }}
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.akademik.curriculums.courses.index', $curriculum->id) }}"
            class="btn btn-secondary mr-2" wire:navigate>Batal</a>
        <button wire:click="saveSelection" class="btn btn-primary" @if (count(array_filter($selectedCourses)) === 0) disabled @endif>
            Tambahkan {{ count(array_filter($selectedCourses)) }} MK yang Dipilih
        </button>
    </div>
</div>
