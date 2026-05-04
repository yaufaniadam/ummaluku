<?php
define('LARAVEL_START', microtime(true));
$basePath = '/var/www/html/portal.ummaluku.ac.id';
require $basePath . '/vendor/autoload.php';
$app = require_once $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\Student;
use App\Models\ClassEnrollment;
use Illuminate\Support\Facades\DB;

Application::where('status', 'diterima')
    ->with(['prospective.user', 'reRegistrationInvoice'])
    ->get()
    ->each(function($app) {
        // Jika invoice belum ada atau belum LUNAS, maka kembalikan ke Camaru
        if (!$app->reRegistrationInvoice || $app->reRegistrationInvoice->status !== 'paid') {
            $user = $app->prospective->user;
            
            DB::transaction(function() use ($user, $app) {
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    // Hapus KRS otomatis jika ada
                    ClassEnrollment::where('student_id', $student->id)->delete();
                    // Hapus record mahasiswa (NIM hilang)
                    $student->delete();
                    echo "Deleted Student record for {$user->name} (NIM removed)\n";
                }
                
                // Kembalikan Role ke Camaru
                $user->syncRoles(['Camaru']);
                echo "Reverted role to Camaru for {$user->name}\n";
            });
        }
    });
