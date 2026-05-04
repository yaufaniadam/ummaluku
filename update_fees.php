<?php
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ReRegistrationInvoice;
use Carbon\Carbon;

ReRegistrationInvoice::with('installments')
    ->where('status', 'unpaid')
    ->get()
    ->each(function(\$inv) {
        \$count = \$inv->installments->count();
        if (\$count == 1) {
            \$amt = Carbon::now()->lt('2026-05-30') ? 2900000 : 2957000;
            \$inv->installments->first()->update(['amount' => \$amt]);
        } elseif (\$count == 2) {
            \$inv->installments->each(function(\$inst) {
                \$inst->update(['amount' => 2957000 / 2]);
            });
        }
        \$inv->update(['total_amount' => 2957000]);
        echo "Updated Invoice #{\$inv->id}\n";
    });
