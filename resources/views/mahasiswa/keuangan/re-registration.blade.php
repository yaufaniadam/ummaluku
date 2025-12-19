@extends('adminlte::page')

@section('title', 'Detail Tagihan Registrasi Ulang')

@section('content_header')
    <h1>Detail Tagihan Registrasi Ulang</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">Ringkasan Tagihan</h3>
                    <p class="text-muted text-center">{{ $invoice->invoice_number }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Total Tagihan</b> <a class="float-right text-primary">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Jatuh Tempo</b> <a class="float-right">{{ \Carbon\Carbon::parse($invoice->due_date)->isoFormat('D MMMM YYYY') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right">
                                @if ($invoice->status == 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($invoice->status == 'unpaid')
                                    <span class="badge badge-warning">Belum Lunas</span>
                                @elseif($invoice->status == 'partially_paid')
                                    <span class="badge badge-primary">Dibayar Sebagian</span>
                                @else
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rincian Pembayaran / Cicilan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                                <th>Batas Waktu</th>
                                <th>Status</th>
                                <th style="width: 150px">Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->installments as $installment)
                                <tr>
                                    <td>
                                        @if ($invoice->installments->count() > 1)
                                            Cicilan ke-{{ $installment->installment_number }}
                                        @else
                                            Pembayaran Penuh Registrasi Ulang
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                                    <td>{{ $installment->due_date->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = 'warning';
                                            if($installment->status == 'paid') $badgeClass = 'success';
                                            if($installment->status == 'pending_verification') $badgeClass = 'info';
                                            if($installment->status == 'rejected') $badgeClass = 'danger';
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ Str::title(str_replace('_', ' ', $installment->status)) }}</span>
                                    </td>
                                    <td>
                                        @if($installment->proof_of_payment)
                                            <a href="{{ Storage::url($installment->proof_of_payment) }}" target="_blank" class="btn btn-xs btn-info">
                                                <i class="fas fa-eye"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-muted small italic">Belum diupload</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <p class="text-muted small">
                        <i class="fas fa-info-circle mr-1"></i>
                        Jika Anda ingin melakukan pembayaran atau upload bukti baru, silakan hubungi Bagian Keuangan atau melalui portal PMB jika masih aktif.
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop
