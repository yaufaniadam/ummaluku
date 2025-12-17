<?php

namespace App\Livewire\Keuangan\Transaction;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Form extends Component
{
    public $transactionId;
    public $amount;
    public $transaction_date;
    public $category_id;
    public $description;
    public $type; // passed from parent or determined

    protected $listeners = ['createTransaction' => 'create', 'editTransaction' => 'edit'];

    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'transaction_date' => 'required|date',
        'category_id' => 'required|exists:transaction_categories,id',
        'description' => 'nullable|string',
    ];

    public function mount($type)
    {
        $this->type = $type;
    }

    public function render()
    {
        $categories = TransactionCategory::where('type', $this->type)->get();
        return view('livewire.keuangan.transaction.form', compact('categories'));
    }

    public function create()
    {
        $this->reset(['transactionId', 'amount', 'transaction_date', 'category_id', 'description']);
        $this->transaction_date = date('Y-m-d');
        $this->dispatch('show-transaction-modal');
    }

    public function edit($id)
    {
        $this->reset(['transactionId', 'amount', 'transaction_date', 'category_id', 'description']);
        $this->transactionId = $id;
        $transaction = Transaction::find($id);

        $this->amount = $transaction->amount;
        $this->transaction_date = $transaction->transaction_date->format('Y-m-d');
        $this->category_id = $transaction->category_id;
        $this->description = $transaction->description;

        $this->dispatch('show-transaction-modal');
    }

    public function save()
    {
        $this->validate();

        Transaction::updateOrCreate(
            ['id' => $this->transactionId],
            [
                'amount' => $this->amount,
                'transaction_date' => $this->transaction_date,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'type' => $this->type,
                'user_id' => Auth::id(),
            ]
        );

        $this->dispatch('hide-transaction-modal');
        $this->dispatch('transactionSaved');
        $this->dispatch('success', $this->transactionId ? 'Transaksi diperbarui.' : 'Transaksi ditambahkan.');
    }
}
