<?php

namespace App\Livewire\Keuangan\Transaction;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type; // 'income' or 'expense' set via mount or URL
    public $dateStart;
    public $dateEnd;

    protected $listeners = ['transactionSaved' => '$refresh'];

    public function mount()
    {
        // Determine type from Route name if not explicitly set
        if (request()->routeIs('admin.keuangan.income.index')) {
            $this->type = 'income';
        } elseif (request()->routeIs('admin.keuangan.expense.index')) {
            $this->type = 'expense';
        }
    }

    public function render()
    {
        $transactions = Transaction::with(['category', 'user'])
            ->where('type', $this->type)
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->dateStart, function ($query) {
                $query->whereDate('transaction_date', '>=', $this->dateStart);
            })
            ->when($this->dateEnd, function ($query) {
                $query->whereDate('transaction_date', '<=', $this->dateEnd);
            })
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.keuangan.transaction.index', [
            'transactions' => $transactions
        ])->extends('adminlte::page')->section('content');
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            // Optional: Prevent deleting auto-generated transactions if needed
            // if ($transaction->reference_id) { ... }

            $transaction->delete();
            $this->dispatch('success', 'Transaksi berhasil dihapus.');
        }
    }
}
