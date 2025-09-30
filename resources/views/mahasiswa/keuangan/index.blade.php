@extends('adminlte::page')

@section('title', 'Riwayat Keuangan')

@section('content_header')
    <h1>Riwayat Keuangan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Tagihan Semester</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>No. Tagihan</th>
                        <th>Semester</th>
                        <th>Jumlah Tagihan</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->academicYear->name }}</td>
                            <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->isoFormat('D MMMM YYYY') }}</td>
                            <td>
                                @if ($invoice->status == 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($invoice->status == 'unpaid')
                                    <span class="badge badge-warning">Belum Lunas</span>
                                @else
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                @endif
                            </td>
                            <td>
                                {{-- Tombol ini akan kita fungsikan nanti --}}
                                <a href="{{ route('mahasiswa.keuangan.show', $invoice->id) }}" class="btn btn-primary btn-sm"
                                    wire:navigate>
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
