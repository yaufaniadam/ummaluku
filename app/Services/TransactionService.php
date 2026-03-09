<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    /**
     * Record a student payment as an income transaction.
     *
     * @param Model $paymentModel The source model (AcademicPayment or ReRegistrationInstallment)
     * @param int|float $amount The amount paid
     * @param string $description Description for the transaction
     */
    public static function recordPayment(Model $paymentModel, $amount, string $description)
    {
        // Find or create the default category
        // Using firstOrCreate ensures we don't duplicate, but relies on name stability.
        $category = TransactionCategory::firstOrCreate(
            ['name' => 'Pembayaran Mahasiswa', 'type' => 'income'],
            ['description' => 'Pemasukan otomatis dari pembayaran SPP/PMB mahasiswa']
        );

        return Transaction::create([
            'category_id' => $category->id,
            'amount' => $amount,
            'transaction_date' => now(),
            'description' => $description,
            'type' => 'income',
            'reference_id' => $paymentModel->getKey(),
            'reference_type' => $paymentModel->getMorphClass(),
            'user_id' => Auth::id(),
        ]);
    }
}
