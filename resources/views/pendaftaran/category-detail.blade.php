@extends('layouts.frontend')

@section('title', $category->name)

@section('content')
    <header class="py-5">
        <div class="container px-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xxl-6">
                    <div class="text-center my-5">
                        <h1 class="fw-bolder mb-3">{{ $category->name }}</h1>
                        <p class="lead fw-normal text-muted mb-4">{{ $category->description }}</p>
                        <p class="lead fw-normal text-muted mb-4">Biaya Pendaftaran: <strong>Rp
                                {{ number_format($category->price, 0, ',', '.') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5" id="jadwal">
        <div class="container px-5 my-5">
            <div class="text-center mb-5">
                <h2 class="fw-bolder">Jadwal Gelombang yang Tersedia</h2>
                <p class="lead fw-normal text-muted">Pilih gelombang pendaftaran di bawah ini untuk melanjutkan.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @forelse ($category->batches->sortByDesc('start_date') as $batch)
                        <div class="card mb-3">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">{{ $batch->name }} - {{ $batch->year }}</h5>
                                    <p class="card-text text-muted">
                                        Periode: {{ $batch->start_date->format('d M Y') }} s/d
                                        {{ $batch->end_date->format('d M Y') }}
                                    </p>
                                </div>
                                {{-- LOGIKA BARU DIMULAI DI SINI --}}
                                @if ($batch->end_date->isPast())
                                    {{-- Jika tanggal akhir gelombang sudah lewat, matikan tombolnya --}}
                                    <a href="#" class="btn btn-secondary disabled" aria-disabled="true">
                                        Pendaftaran Ditutup
                                    </a>
                                @else
                                    {{-- Jika masih berlangsung, tampilkan tombol daftar yang aktif --}}
                                    <a href="{{ route('pendaftaran.form', ['type' => $category->slug, 'batch' => $batch->id]) }}"
                                        class="btn btn-warning">
                                        Daftar di Gelombang Ini
                                    </a>
                                @endif
                                {{-- AKHIR DARI LOGIKA BARU --}}
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning text-center">
                            Saat ini belum ada gelombang pendaftaran yang dibuka untuk jalur ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
