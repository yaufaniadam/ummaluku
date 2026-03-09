@extends('adminlte::page')

@section('title', 'Executive Dashboard')

@section('content_header')
    <h1>Executive Dashboard</h1>
@stop

@section('content')
    <!-- Baris 1: Statistik Utama -->
    <div class="row">
        <!-- Mahasiswa -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-white" style="border: 1px solid #ddd;">
                <div class="inner">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Mahasiswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('executive.students.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Dosen -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-white" style="border: 1px solid #ddd;">
                <div class="inner">
                    <h3>{{ $totalLecturers }}</h3>
                    <p>Total Dosen</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('executive.lecturers.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>


        <!-- Tendik -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-white" style="border: 1px solid #ddd;">
                <div class="inner">
                    <h3>{{ $totalTendik }}</h3>
                    <p>Tenaga Kependidikan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <a href="{{ route('executive.staff.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>


        <!-- Mahasiswa Diterima (Admisi) -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-white" style="border: 1px solid #ddd;">
                <div class="inner">
                    <h3>{{ $acceptedApplicants }}</h3>
                    <p>Mahasiswa Diterima</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ route('executive.pmb.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Baris 2: Statistik PMB & Grafik -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Statistik Mahasiswa per Prodi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="studentProgramChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Info Box PMB -->
            <div class="info-box mb-3 bg-info">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pendaftar (PMB)</span>
                    <span class="info-box-number">{{ $totalApplicants }}</span>
                </div>
            </div>

            <div class="info-box mb-3 bg-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mahasiswa Baru Diterima</span>
                    <span class="info-box-number">{{ $acceptedApplicants }}</span>
                </div>
            </div>

             <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Status Mahasiswa</h3>
                </div>
                <div class="card-body">
                     <canvas id="studentStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data Mahasiswa per Prodi
    var programLabels = {!! json_encode($studentsPerProgram->pluck('label')) !!};
    var programData = {!! json_encode($studentsPerProgram->pluck('value')) !!};

    var ctxProgram = document.getElementById('studentProgramChart').getContext('2d');
    var programChart = new Chart(ctxProgram, {
        type: 'bar', // atau 'pie'
        data: {
            labels: programLabels,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: programData,
                backgroundColor: 'rgba(60, 141, 188, 0.9)',
                borderColor: 'rgba(60, 141, 188, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Data Status Mahasiswa (Pie Chart)
    var statusLabels = {!! json_encode($studentStatusStats->keys()) !!};
    var statusData = {!! json_encode($studentStatusStats->values()) !!};

    // Generate warna random/tetap
    var colors = ['#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6c757d'];

    var ctxStatus = document.getElementById('studentStatusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: colors,
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });
</script>
@stop
