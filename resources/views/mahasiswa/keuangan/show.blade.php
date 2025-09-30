@extends('adminlte::page')

@section('title', 'Detail Tagihan')

@section('content_header')
    <h1 class="mb-1">Detail Tagihan</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('mahasiswa.keuangan.index') }}" wire:navigate>Riwayat Keuangan</a> > {{ $invoice->invoice_number }}
    </h5>
@stop

@section('content')
   

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rincian Tagihan Semester {{ $invoice->academicYear->name }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->items as $item)
                                    <tr>
                                        <td>{{ $item->description }}</td>
                                        <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td class="text-right">TOTAL TAGIHAN</td>
                                    <td class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status & Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Status:</strong>
                            @if ($invoice->status == 'paid')
                                <span class="badge badge-success">Lunas</span>
                            @elseif($invoice->status == 'unpaid')
                                <span class="badge badge-warning">Belum Lunas</span>
                            @else
                                <span class="badge badge-danger">Jatuh Tempo</span>
                            @endif
                        </p>
                        <p><strong>Jatuh Tempo:</strong>
                            {{ \Carbon\Carbon::parse($invoice->due_date)->isoFormat('D MMMM YYYY') }}</p>

                        <hr>

                        @if ($invoice->status == 'unpaid')
                            {{-- Di sini kita bisa tambahkan informasi rekening --}}
                            <h6>Informasi Pembayaran</h6>
                            <p>Silakan lakukan pembayaran ke rekening berikut:</p>
                            <p><strong>Bank XYZ</strong><br>No. Rek: 123-456-7890<br>A/N: Yayasan Universitas Muhammadiyah
                                Maluku</p>
                            <hr>
                            {{-- Form untuk upload bukti bayar bisa kita buat di sini nanti --}}
                            <a href="{{ route('mahasiswa.keuangan.confirm.create', $invoice->id) }}"
                                class="btn btn-success btn-block" wire:navigate>
                                Konfirmasi Pembayaran
                            </a>
                        @else
                            <p class="text-success"><i class="fas fa-check-circle"></i> Pembayaran untuk tagihan ini sudah
                                lunas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

@stop
