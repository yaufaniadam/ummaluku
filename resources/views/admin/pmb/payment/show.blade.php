@extends('adminlte::page')
@section('title', 'Detail Verifikasi Pembayaran')
@section('content_header')
    <h1>Detail Pembayaran: {{ $invoice->invoice_number }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Informasi Pendaftar</h3></div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $invoice->application->prospective->user->name }}</p>
                <p><strong>No. Pendaftaran:</strong> {{ $invoice->application->registration_number }}</p>
                <p><strong>Total Tagihan:</strong> <span class="h5">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span></p>
                <p><strong>Status Tagihan:</strong> <span class="badge badge-lg badge-primary">{{ Str::title(str_replace('_', ' ', $invoice->status)) }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
             <div class="card-header"><h3 class="card-title">Rincian & Verifikasi Cicilan</h3></div>
             <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->installments as $installment)
                        <tr>
                            <td>Cicilan ke-{{ $installment->installment_number }}</td>
                            <td>Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($installment->status == 'paid') <span class="badge badge-success">Lunas</span>
                                @elseif($installment->status == 'pending_verification') <span class="badge badge-warning">Menunggu Verifikasi</span>
                                @elseif($installment->status == 'rejected') <span class="badge badge-danger">Ditolak</span>
                                @else <span class="badge badge-secondary">Belum Dibayar</span>
                                @endif
                            </td>
                            <td>
                                @if($installment->status == 'pending_verification')
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#verificationModal-{{ $installment->id }}">
                                        Verifikasi
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="verificationModal-{{ $installment->id }}" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel-{{ $installment->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="verificationModalLabel-{{ $installment->id }}">Verifikasi Cicilan ke-{{ $installment->installment_number }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    @if($installment->proof_of_payment)
                                                        <img src="{{ Storage::url($installment->proof_of_payment) }}" class="img-fluid" alt="Bukti Pembayaran" style="max-height: 500px;">
                                                    @else
                                                        <p class="text-danger">Tidak ada bukti pembayaran yang diunggah.</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.pmb.payment.reject', $installment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menolak?')">Tolak</button>
                                                    </form>
                                                    <form action="{{ route('admin.pmb.payment.approve', $installment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" onclick="return confirm('Anda yakin ingin menyetujui?')">Setujui</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($installment->status == 'paid')
                                    <span class="text-success">Diverifikasi oleh {{ $installment->verifiedBy->name ?? 'Sistem' }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
             </div>
        </div>
    </div>
</div>
@stop