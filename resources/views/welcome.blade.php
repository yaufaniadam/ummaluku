@extends('tablar::page')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Penerimaan Mahasiswa Baru
                </h2>
                <div class="text-muted mt-1">
                    Universitas Muhammadiyah Maluku - Tahun Ajaran 2025/2026
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row row-cards">
            @forelse ($categories as $category)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $category->name }}</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $category->description }}</p>
                            <p>Biaya Pendaftaran: <strong>Rp {{ number_format($category->price, 0, ',', '.') }}</strong></p>
                            
                            <hr>
                            <p class="mb-2"><strong>Gelombang yang dibuka:</strong></p>
                            <div class="d-grid gap-2">
                                {{-- Perulangan kedua untuk setiap gelombang di dalam kategori ini --}}
                                @foreach ($category->batches as $batch)
                                    <a href="{{ route('pendaftaran.form', ['type' => $category->slug, 'batch' => $batch->id]) }}" class="btn btn-outline-primary">
                                        Daftar {{ $batch->name }} &nbsp;
                                        <small class="d-block text-muted">
                                            ({{ $batch->start_date->format('d M') }} - {{ $batch->end_date->format('d M Y') }})
                                        </small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-door-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 3l18 18"></path><path d="M13 11v-1a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v4h2"></path><path d="M10 10v-5a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v10m-2 2h-5a2 2 0 0 1 -2 -2v-2"></path></svg>
                        </div>
                        <p class="empty-title">Pendaftaran Belum Dibuka</p>
                        <p class="empty-subtitle text-muted">
                            Saat ini belum ada jalur atau gelombang pendaftaran yang aktif. Silakan kembali lagi nanti.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection