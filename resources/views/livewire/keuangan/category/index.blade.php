<div>
    @section('title', 'Kategori Transaksi')
    @section('content_header')
        <h1>Kategori Transaksi</h1>
    @stop

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kategori</h3>
            <div class="card-tools">
                <button wire:click="$dispatch('createCategory')" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari Kategori...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="typeFilter" class="form-control">
                        <option value="">Semua Tipe</option>
                        <option value="income">Pemasukan (Income)</option>
                        <option value="expense">Pengeluaran (Expense)</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->type == 'income')
                                    <span class="badge badge-success">Pemasukan</span>
                                @else
                                    <span class="badge badge-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <button wire:click="$dispatch('editCategory', { id: {{ $category->id }} })" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $category->id }})"
                                        wire:confirm="Yakin ingin menghapus kategori ini?"
                                        class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    @livewire('keuangan.category.form')
</div>
