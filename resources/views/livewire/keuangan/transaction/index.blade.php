<div>
    @section('title', $type == 'income' ? 'Uang Masuk' : 'Uang Keluar')
    @section('content_header')
        <h1>{{ $type == 'income' ? 'Daftar Pemasukan' : 'Daftar Pengeluaran' }}</h1>
    @stop

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data {{ $type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}</h3>
            <div class="card-tools">
                <button wire:click="$dispatch('createTransaction')" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah {{ $type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari Deskripsi/Kategori...">
                </div>
                <div class="col-md-3">
                    <input type="date" wire:model.live="dateStart" class="form-control" placeholder="Tanggal Mulai">
                </div>
                <div class="col-md-3">
                    <input type="date" wire:model.live="dateEnd" class="form-control" placeholder="Tanggal Akhir">
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $transaction->category->name }}</td>
                            <td>
                                {{ $transaction->description }}
                                @if($transaction->reference_type)
                                    <br><small class="text-muted">Ref: {{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }}</small>
                                @endif
                            </td>
                            <td class="text-right">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>
                                @if(!$transaction->reference_type)
                                    {{-- Only allow editing manual transactions, auto-synced ones should be immutable or handled with care --}}
                                    <button wire:click="$dispatch('editTransaction', { id: {{ $transaction->id }} })" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $transaction->id }})"
                                            wire:confirm="Yakin ingin menghapus transaksi ini?"
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="badge badge-info">Otomatis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    @livewire('keuangan.transaction.form', ['type' => $type])
</div>
