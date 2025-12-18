<?php

namespace Tests\Feature\Finance;

use App\Models\AcademicInvoice;
use App\Models\AcademicPayment;
use App\Models\ReRegistrationInvoice;
use App\Models\ReRegistrationInstallment;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_academic_payment_creates_income_transaction()
    {
        // Setup
        $user = User::factory()->create();
        $user->assignRole('Super Admin');
        $student = Student::factory()->create();

        $invoice = AcademicInvoice::create([
            'student_id' => $student->id,
            'invoice_number' => 'INV-001',
            'amount' => 1000000,
            'status' => 'unpaid'
        ]);

        $payment = AcademicPayment::create([
            'academic_invoice_id' => $invoice->id,
            'payment_date' => now(),
            'amount' => 1000000,
            'proof_url' => 'dummy.jpg',
            'status' => 'pending'
        ]);

        // Act
        $response = $this->actingAs($user)->post(route('admin.keuangan.payment-verification.approve', $payment));

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('academic_payments', ['id' => $payment->id, 'status' => 'verified']);

        // Verify Transaction Created
        $this->assertDatabaseHas('transaction_categories', ['name' => 'Pembayaran Mahasiswa', 'type' => 'income']);
        $this->assertDatabaseHas('transactions', [
            'amount' => 1000000,
            'type' => 'income',
            'reference_id' => $payment->id,
            'reference_type' => AcademicPayment::class
        ]);
    }
}
