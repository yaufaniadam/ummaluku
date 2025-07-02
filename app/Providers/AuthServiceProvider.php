<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    // app/Providers/AuthServiceProvider.php

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            // PERIKSA DULU APAKAH $user ADA, BARU CEK ROLENYA
            if ($user && $user->hasRole('Super Admin')) {
                return true;
            }
            return null; // Jika bukan superadmin atau user adalah guest, lanjutkan pengecekan normal
        });
    }
}
