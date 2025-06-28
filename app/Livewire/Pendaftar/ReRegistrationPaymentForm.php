<?php
namespace App\Livewire\Pendaftar;

use App\Models\ReRegistrationInvoice;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReRegistrationPaymentForm extends Component
{
    use WithFileUploads;

    public ReRegistrationInvoice $invoice;
    public $proof_of_payment;

    public function uploadProof()
    {
        $this->validate([
            'proof_of_payment' => 'required|image|max:2048',
        ]);

        $filePath = $this->proof_of_payment->store('re_registration_proofs/' . $this->invoice->application_id, 'public');

        $this->invoice->update([
            'proof_of_payment' => $filePath,
            'status' => 'pending_verification',
        ]);

        session()->flash('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi oleh admin.');
        return $this->redirect(route('pendaftar.registrasi'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pendaftar.re-registration-payment-form');
    }
}