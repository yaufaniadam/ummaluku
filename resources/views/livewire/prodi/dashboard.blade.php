@section('title', 'Dashboard Prodi')

@section('content_header')
    <h1>Dashboard Program Studi {{ $programName }}</h1>
@endsection

<div>
    {{-- Info Boxes --}}
    <div class="row">
        {{-- Total Active Students --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalActiveStudents }}</h3>
                    <p>Mahasiswa Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Pending KRS Approval --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingKrsCount }}</h3>
                    <p>Menunggu Persetujuan KRS</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                {{-- Assuming the route for KRS approval exists --}}
                <a href="{{ route('prodi.krs-approval.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Active Classes --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeClassesCount }}</h3>
                    <p>Kelas Aktif (Semester Ini)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('prodi.course-class.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Total Courses --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalCoursesCount }}</h3>
                    <p>Total Matakuliah</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('prodi.course.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
