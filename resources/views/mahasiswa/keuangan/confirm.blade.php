@extends('adminlte::page')

@section('title', 'Konfirmasi Pembayaran')

@section('content_header')
    <h1 class="mb-1">Konfirmasi Pembayaran</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('mahasiswa.keuangan.index') }}" wire:navigate>Riwayat Keuangan</a> > 
        <a href="{{ route('mahasiswa.keuangan.show', $invoice->id) }}" wire:navigate>{{ $invoice->invoice_number }}</a> > 
        Konfirmasi
    </h5>
@stop

@section('content')
    <form action="{{ route('mahasiswa.keuangan.confirm', $invoice->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header"><h3 class="card-title">Formulir Konfirmasi</h3></div>
            <div class="card-body">
                <p>Anda akan melakukan konfirmasi pembayaran untuk tagihan <strong>{{ $invoice->invoice_number }}</strong> sebesar <strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>.</p>
                <hr>
                <div class="form-group">
                    <label for="payment_date">Tanggal Bayar</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', now()->format('Y-m-d')) }}">
                    @error('payment_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="amount">Jumlah yang Dibayar</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $invoice->total_amount) }}">
                    @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="proof">Upload Bukti Bayar (JPG, PNG)</label>
                    <input type="file" name="proof" id="proof" class="form-control-file @error('proof') is-invalid @enderror" required>
                    @error('proof') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="notes">Catatan (Opsional)</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('mahasiswa.keuangan.show', $invoice->id) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Kirim Konfirmasi</button>
            </div>
        </div>
    </form>
@stop