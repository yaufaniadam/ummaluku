@extends('adminlte::page')

@section('title', 'Executive Dashboard')

@section('content_header')
    <h1>Executive Dashboard</h1>
@stop

@section('content')
    <!-- Baris 1: 4 Pilar Utama (Summary) -->
    <div class="row">
        <!-- 1. KEUANGAN (Prioritas Utama) -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalRevenue / 1000000, 1) }} M</h3>
                    <p>Total Pendapatan (PMB+SPP)</p>
                    <small>Tunggakan: Rp {{ number_format($totalArrears / 1000000, 1) }} M</small>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                <a href="{{ route('admin.executive.finance.index') }}" class="small-box-footer">Detail Keuangan <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 2. PMB (Growth) -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalApplicants }} / {{ $yieldRate }}%</h3>
                    <p>Pendaftar / Yield Rate</p>
                    <small>Diterima: {{ \App\Models\Application::where('status', 'diterima')->orWhere('status', 'sudah_registrasi')->count() }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ route('admin.executive.pmb.index') }}" class="small-box-footer">Detail PMB <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 3. MAHASISWA (Population) -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Mahasiswa</p>
                    <small>Aktif: {{ $activeStudents }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('admin.executive.student.index') }}" class="small-box-footer">Detail Mahasiswa <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- 4. SDM (Resources) -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalLecturers }}</h3>
                    <p>Total Dosen</p>
                    <small>Tendik: {{ $totalTendik }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('admin.executive.sdm.index') }}" class="small-box-footer">Detail SDM <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Baris 2: Charts -->
    <div class="row">
        <!-- Chart Mahasiswa per Prodi -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Sebaran Mahasiswa per Prodi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="studentProgramChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary & Quick Stats -->
        <div class="col-md-4">
             <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Status Mahasiswa</h3>
                </div>
                <div class="card-body">
                     <!-- Kita akan ambil data real via AJAX atau embed JSON nanti, sementara placeholder -->
                     <canvas id="studentStatusChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                </div>
                <div class="card-footer p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Aktif <span class="float-right badge bg-success">{{ $activeStudents }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Non-Aktif <span class="float-right badge bg-danger">{{ $totalStudents - $activeStudents }}</span>
                            </a>
                        </li>
                    </ul>
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
        type: 'bar',
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
                    beginAtZero: true
                }
            }
        }
    });

    // Chart Status Mahasiswa (Simple Doughnut)
    var ctxStatus = document.getElementById('studentStatusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Non-Aktif'],
            datasets: [{
                data: [{{ $activeStudents }}, {{ $totalStudents - $activeStudents }}],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });
</script>
@stop
