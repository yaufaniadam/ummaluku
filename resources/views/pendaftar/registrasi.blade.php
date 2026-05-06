@extends('layouts.frontend')
@section('title', 'Registrasi Ulang')
@section('content')


    <div class="page-header d-print-none pt-5 pb-5 bg-light">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    {{-- Judul Halaman Dinamis --}}
                    <h2 class="page-title">
                        {{ $invoice->application->admissionCategory->name }}
                    </h2>
                    <div class="text-muted mt-1">
                        {{ $invoice->application->batch->name }} - Tahun Ajaran {{ $invoice->application->batch->year }}
                    </div>

                    </p>
                </div>
                <div class="col-md-6 text-lg-end ">
                    <h5 class="card-title">Selamat Datang, {{ $invoice->application->prospective->user->name }}!</h5>
                    <small class="text-muted">No. {{ $invoice->application->registration_number }}</small><br>
                    <span>Status: {{ Str::title(str_replace('_', ' ', $invoice->application->status)) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">

        <x-breadcrumb current="{{ $invoice->application->status }}" />
        <div class="row justify-content-center">
            <div class="col-lg-9">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Card Informasi Tagihan Induk --}}
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Tagihan Anda</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 text-muted">Total biaya registrasi ulang normal:</p>
                        <h4 class="mb-3">Rp 2.957.000</h4>

                        @if(now()->lt(\Carbon\Carbon::parse('2026-06-01')))
                            <div class="alert alert-success bg-success-lt border-success">
                                <p class="mb-0">Dapatkan harga spesial <strong>Rp 2.500.000</strong> jika Anda melakukan pembayaran lunas sebelum <strong>1 Juni 2026</strong>.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card Instruksi Pembayaran --}}
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0"><i class="fas fa-university me-2 text-primary"></i>Instruksi Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <p>Pembayaran dapat dilakukan melalui transfer bank ke rekening berikut:</p>
                        <div class="bg-light p-3 rounded-3">
                            <div class="row align-items-center">
                                <div class="col-sm-4 text-muted">Bank:</div>
                                <div class="col-sm-8"><strong>Bank Syariah Indonesia (BSI)</strong></div>
                            </div>
                            <div class="row mt-2 align-items-center">
                                <div class="col-sm-4 text-muted">No. Rekening:</div>
                                <div class="col-sm-8"><strong class="h3 text-primary mb-0">7167953241</strong></div>
                            </div>
                            <div class="row mt-2 align-items-center">
                                <div class="col-sm-4 text-muted">Nama Rekening:</div>
                                <div class="col-sm-8"><strong>Universitas Muhammadiyah Maluku</strong></div>
                            </div>
                        </div>
                        <p class="mt-3 mb-2 small text-muted"><i class="fas fa-info-circle me-1 text-info"></i> Mohon pastikan nominal yang ditransfer sesuai dengan tagihan Anda. Setelah transfer, silakan upload bukti pembayaran di bawah ini.</p>

                        @if ($invoice->installments->isNotEmpty())
                            <div class="list-group list-group-flush border-top mt-3">
                                @foreach ($invoice->installments as $installment)
                                    <div class="list-group-item px-0 py-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="fw-bold">
                                                    @if ($invoice->installments->count() > 1)
                                                        Cicilan ke-{{ $installment->installment_number }}
                                                    @else
                                                        Pembayaran Penuh Registrasi Ulang
                                                    @endif
                                                </div>
                                                <div class="text-primary h4 mb-1">
                                                    @if($installment->installment_number == 1 && $installment->invoice->installments->count() == 1 && now()->lt(\Carbon\Carbon::parse('2026-06-01')))
                                                        Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                                        <small class="text-muted text-decoration-line-through small ms-1">Rp 2.957.000</small>
                                                    @else
                                                        Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($installment->status == 'unpaid')
                                                        <span class="badge bg-warning text-white">Belum Bayar</span>
                                                    @elseif($installment->status == 'pending_verification')
                                                        <span class="badge bg-info text-white">Menunggu Verifikasi</span>
                                                    @elseif($installment->status == 'paid')
                                                        <span class="badge bg-success text-white">Lunas</span>
                                                    @elseif($installment->status == 'rejected')
                                                        <span class="badge bg-danger text-white">Pembayaran Ditolak</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-auto mt-2 mt-md-0">
                                                @if ($installment->status == 'unpaid' || $installment->status == 'rejected')
                                                    <form action="{{ route('pendaftar.installment.store', $installment) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                                                        <div class="input-group input-group-sm">
                                                            <input type="file" name="proof_of_payment" class="form-control" required>
                                                            <button type="submit" class="btn btn-success">Upload</button>
                                                        </div>
                                                    </form>
                                                @elseif($installment->status == 'pending_verification')
                                                    <a href="{{ route('secure.files', ['path' => $installment->proof_of_payment]) }}" target="_blank" class="btn btn-sm btn-info text-white">Lihat Bukti</a>
                                                @elseif($installment->status == 'paid')
                                                    <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Logika Tampilan Kondisional --}}
                @if ($invoice->installments->isEmpty())
                    {{-- JIKA CICILAN BELUM DIBUAT, TAMPILKAN FORM PILIHAN --}}
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Pilih Skema Pembayaran</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pendaftar.registrasi.scheme') }}" method="POST">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                <p>Silakan pilih bagaimana Anda akan membayar tagihan ini:</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_scheme" id="scheme_full"
                                        value="full" checked>
                                    <label class="form-check-label" for="scheme_full">
                                        @if(now()->lt(\Carbon\Carbon::parse('2026-06-01')))
                                            Bayar Lunas Sekarang (<strong class="text-success">Rp 2.500.000</strong> <del class="text-muted small">Rp 2.957.000</del>)
                                        @else
                                            Bayar Lunas Sekarang (Rp 2.957.000)
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_scheme"
                                        id="scheme_installment" value="installment">
                                    <label class="form-check-label" for="scheme_installment">
                                        Bayar 2x Cicilan (50% sekarang, 50% nanti)
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Lanjutkan ke Pembayaran</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
