@extends('layouts.frontend')
@section('title', 'Registrasi Ulang')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <h2 class="mb-4">Tahap Akhir: Registrasi Ulang</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Card Informasi Tagihan Induk --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Tagihan Anda</h3>
                    </div>
                    <div class="card-body">
                        <p>Total biaya registrasi ulang Anda adalah <strong class="h4">Rp
                                {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>.</p>
                    </div>
                </div>

                {{-- Logika Tampilan Kondisional --}}
                @if ($invoice->installments->isEmpty())
                    {{-- JIKA CICILAN BELUM DIBUAT, TAMPILKAN FORM PILIHAN --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pilih Skema Pembayaran</h3>
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
                                        Bayar Lunas Sekarang (100% - Rp
                                        {{ number_format($invoice->total_amount, 0, ',', '.') }})
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
                @else
                    {{-- JIKA CICILAN SUDAH ADA, TAMPILKAN TABEL CICILAN --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rincian Tagihan Anda</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped card-table">
                                <thead>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Batas Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->installments as $installment)
                                        <tr>
                                            <td>Cicilan ke-{{ $installment->installment_number }}</td>
                                            <td>Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                                            <td>{{ $installment->due_date->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-warning">{{ Str::title(str_replace('_', ' ', $installment->status)) }}</span>
                                            </td>
                                            <td>
                                                @if ($installment->status == 'unpaid')
                                                    {{-- Form upload bukti bayar untuk cicilan ini --}}
                                                    <form action="{{ route('pendaftar.installment.store', $installment) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="installment_id"
                                                            value="{{ $installment->id }}">
                                                        <div class="input-group input-group-sm">
                                                            <input type="file" name="proof_of_payment"
                                                                class="form-control" required>
                                                            <button type="submit" class="btn btn-success">Upload</button>
                                                        </div>
                                                    </form>
                                                @elseif($installment->status == 'pending_verification')
                                                    <a href="{{ Storage::url($installment->proof_of_payment) }}"
                                                        target="_blank">Lihat Bukti</a>
                                                @elseif($installment->status == 'paid')
                                                    <span class="text-success"><i class="fas fa-check-circle"></i>
                                                        Lunas</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
