@extends('adminlte::page')

@section('title', 'Dashboard PMB')

@section('content_header')
    <h1>Dashboard Penerimaan Mahasiswa Baru (PMB)</h1>
    @if ($activeBatch)
        <h5 class="font-weight-light">Gelombang Aktif: {{ $activeBatch->name }}
            ({{ $activeBatch->academicYear->name ?? '' }})</h5>
    @else
        <h5 class="font-weight-light text-danger">Tidak ada gelombang pendaftaran yang aktif.</h5>
    @endif
@stop

@section('content')
    {{-- Baris 1: Widget Lama (Existing) --}}
    <div class="row">
        <div class="col-lg-4 col-6">
            <x-stat-box title="Total Pendaftar" value="{{ $totalPendaftar }}" icon="fas fa-users" color="info"
                url="{{ route('admin.pmb.pendaftaran.index') }}" />
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Diterima" value="{{ $totalDiterima }}" icon="fas fa-file-signature" color="success"
                url="{{ route('admin.pmb.diterima.index') }}" />
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Sudah Registrasi" value="{{ $totalRegistrasi }}" icon="fas fa-user-check" color="warning"
                url="{{ route('admin.pmb.diterima.index') }}" />
        </div>

    </div>

    {{-- Baris 2: Widget Baru --}}
    <div class="row">
        {{-- Diterima tapi belum bayar --}}
        <div class="col-lg-4 col-6">
            <x-info-box title="Menunggu Verifikasi Pembayaran" value="{{ $awaitingVerificationPayment }}" icon="fas fa-receipt" color="warning"
                url="{{ route('admin.pmb.pendaftaran.index') }}" />
        </div>
        <div class="col-lg-4 col-6">
            <x-info-box title="Menunggu Verifikasi Dokumen" value="{{ $waitingForVerification }}" icon="fas fa-file" color="danger"
                url="{{ route('admin.pmb.pendaftaran.index') }}" />
        </div>        
    </div>

    <div class="row">
        {{-- Chart Pendaftar per Program Studi --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Grafik Mahasiswa Diterima per Program Studi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="acceptedChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart Pendaftar per Gelombang --}}
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pendaftar per Gelombang</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="batchChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Tabel To-Do List Admin --}}
        <div class="col-md-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        Daftar Antrian Verifikasi (Dokumen & Pembayaran)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tipe</th>
                                    <th>Nama Calon Mahasiswa</th>
                                    <th>Tanggal Update</th>
                                    <th>Status</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todoList as $item)
                                    <tr>
                                        <td>
                                            @if ($item['type'] == 'Verifikasi Dokumen')
                                                <span class="badge badge-info"><i class="fas fa-file-alt"></i>
                                                    Dokumen</span>
                                            @else
                                                <span class="badge badge-success"><i class="fas fa-money-bill"></i>
                                                    Pembayaran</span>
                                            @endif
                                        </td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['date'] ? $item['date']->format('d M Y H:i') : '-' }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $item['badge'] }}">{{ $item['status_label'] }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ $item['url'] }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-search"></i> Proses
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                            Tidak ada antrian verifikasi saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <span class="text-muted text-sm">Menampilkan semua item yang membutuhkan tindakan admin.</span>
                </div>
            </div>
        </div>
    </div>
@stop

@section('plugins.Chartjs', true)

@section('js')
    <script>
        $(function() {
            // --- CHART 1: Diterima per Prodi ---
            var labels = @json($chartLabels);
            var dataValues = @json($chartValues);

            var ctx = document.getElementById('acceptedChart').getContext('2d');
            var acceptedChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Mahasiswa Diterima',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: dataValues
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1
                            }
                        }]
                    }
                }
            });

            // --- CHART 2: Pendaftar per Gelombang ---
            var batchLabels = @json($batchChartLabels);
            var batchValues = @json($batchChartValues);

            var ctx2 = document.getElementById('batchChart').getContext('2d');
            var batchChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: batchLabels,
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        backgroundColor: 'rgba(23, 162, 184, 0.9)', // Info Color
                        borderColor: 'rgba(23, 162, 184, 0.8)',
                        pointRadius: false,
                        pointColor: '#17a2b8',
                        pointStrokeColor: 'rgba(23, 162, 184, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(23, 162, 184, 1)',
                        data: batchValues
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1
                            }
                        }]
                    }
                }
            });
        });
    </script>
@stop
