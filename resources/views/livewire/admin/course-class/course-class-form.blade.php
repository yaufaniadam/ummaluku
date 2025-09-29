<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $courseClass ? 'Edit' : 'Tambah' }} Kelas Baru</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="course_id">Mata Kuliah</label>
                            <select wire:model="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }} ({{$course->sks}} SKS)</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lecturer_id">Dosen Pengampu</label>
                            <select wire:model="lecturer_id" id="lecturer_id" class="form-control @error('lecturer_id') is-invalid @enderror">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->full_name_with_degree }}</option>
                                @endforeach
                            </select>
                            @error('lecturer_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Kelas</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Contoh: A, B, Pagi, Malam">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="capacity">Kapasitas</label>
                            <input type="number" wire:model="capacity" class="form-control @error('capacity') is-invalid @enderror" id="capacity" placeholder="Jumlah maksimal mahasiswa">
                            @error('capacity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <p class="font-weight-bold">Jadwal & Ruangan (Opsional)</p>
                <div class="row">
                    <div class="col-md-3"><div class="form-group">
                        <label for="day">Hari</label>
                        <select wire:model="day" id="day" class="form-control">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div></div>
                    <div class="col-md-3"><div class="form-group">
                        <label for="start_time">Jam Mulai</label>
                        <input type="time" wire:model="start_time" class="form-control @error('start_time') is-invalid @enderror" id="start_time">
                    </div></div>
                    <div class="col-md-3"><div class="form-group">
                        <label for="end_time">Jam Selesai</label>
                        <input type="time" wire:model="end_time" class="form-control @error('end_time') is-invalid @enderror" id="end_time">
                    </div></div>
                    <div class="col-md-3"><div class="form-group">
                        <label for="room">Ruangan</label>
                        <input type="text" wire:model="room" class="form-control" id="room" placeholder="Contoh: Lab Komputer 1">
                    </div></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.akademik.academic-years.programs.course-classes.index', ['academic_year' => $academicYear, 'program' => $program]) }}" class="btn btn-secondary" wire:navigate>Batal</a>
            </div>
        </div>
    </form>
</div>