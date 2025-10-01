@extends('adminlte::page')
@section('title', 'Detail Verifikasi Pembayaran')
@section('content_header')
    <h1 class="mb-1">Detail Verifikasi Pembayaran</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.keuangan.payment-verification.index') }}">Verifikasi Pembayaran</a> > {{ $invoice->invoice_number }}
    </h5>
@stop
@section('content')
    <div class="row">
        <div class="col-md-7">
            {{-- Card Detail Tagihan --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title">Rincian Tagihan</h3></div>
                <div class="card-body">
                    <p><strong>No. Tagihan:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Mahasiswa:</strong> {{ $invoice->student->user->name }} (NIM: {{ $invoice->student->nim }})</p>
                    <p><strong>Semester:</strong> {{ $invoice->academicYear->name }}</p>
                    <table class="table table-sm table-bordered">
                        @foreach ($invoice->items as $item)
                        <tr><td>{{ $item->description }}</td><td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right">TOTAL</td>
                            <td class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            {{-- Card Verifikasi --}}
            @if ($paymentToVerify)
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Konfirmasi Pembayaran dari Mahasiswa</h3></div>
                    <div class="card-body">
                        <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($paymentToVerify->payment_date)->isoFormat('D MMMM YYYY') }}</p>
                        <p><strong>Jumlah Dibayar:</strong> Rp {{ number_format($paymentToVerify->amount, 0, ',', '.') }}</p>
                        <p><strong>Catatan:</strong> {{ $paymentToVerify->notes ?? '-' }}</p>
                        <p><strong>Bukti Pembayaran:</strong></p>
                        <a href="{{ Storage::url($paymentToVerify->proof_url) }}" target="_blank">
                            <img src="{{ Storage::url($paymentToVerify->proof_url) }}" class="img-fluid" alt="Bukti Pembayaran">
                        </a>
                    </div>
                    <div class="card-footer">
                        {{-- Form untuk Aksi --}}
                        <form action="#" method="POST" class="d-inline"> @csrf <button type="submit" class="btn btn-danger">Tolak</button></form>
                        <form action="#" method="POST" class="d-inline float-right"> @csrf <button type="submit" class="btn btn-success">Setujui & Verifikasi</button></form>
                    </div>
                </div>
            @else
                <div class="alert alert-info">Status tagihan saat ini adalah <strong>{{ $invoice->status }}</strong>. Tidak ada pembayaran yang perlu diverifikasi.</div>
            @endif
        </div>
    </div>
@stop