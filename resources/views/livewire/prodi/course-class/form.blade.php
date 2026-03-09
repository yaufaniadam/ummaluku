<div>
    @section('title', $courseClass ? 'Edit Kelas' : 'Tambah Kelas')
    @section('content_header')
        <h1>{{ $courseClass ? 'Edit Kelas' : 'Tambah Kelas' }}</h1>
    @endsection

    <div class="card">
        <form wire:submit.prevent="save">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                            <label>Matakuliah</label>
                            <select wire:model="course_id" class="form-control @error('course_id') is-invalid @enderror">
                                <option value="">-- Pilih Matakuliah --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->code }} - {{ $course->name }} ({{ $course->sks }} SKS)</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label>Tahun Ajaran</label>
                            <select wire:model="academic_year_id" class="form-control @error('academic_year_id') is-invalid @enderror">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} ({{ $year->semester }})</option>
                                @endforeach
                            </select>
                            @error('academic_year_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: A, B, Regular">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Dosen Pengampu</label>
                    <select wire:model="lecturer_id" class="form-control @error('lecturer_id') is-invalid @enderror">
                        <option value="">-- Pilih Dosen (Opsional) --</option>
                        @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }}</option>
                        @endforeach
                    </select>
                    @error('lecturer_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Kapasitas</label>
                    <input type="number" wire:model="capacity" class="form-control @error('capacity') is-invalid @enderror" min="1">
                    @error('capacity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="row">
                    <div class="col-md-3">
                         <div class="form-group">
                            <label>Hari</label>
                            <select wire:model="day" class="form-control @error('day') is-invalid @enderror">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                            @error('day') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jam Mulai</label>
                            <input type="time" wire:model="start_time" class="form-control @error('start_time') is-invalid @enderror">
                            @error('start_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jam Selesai</label>
                            <input type="time" wire:model="end_time" class="form-control @error('end_time') is-invalid @enderror">
                            @error('end_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ruangan</label>
                            <input type="text" wire:model="room" class="form-control @error('room') is-invalid @enderror" placeholder="Contoh: R.201">
                            @error('room') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('prodi.course-class.index') }}" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
</div>
