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
    public function boot()
    {
        $this->registerPolicies();

        // Secara implisit memberikan semua hak akses ke super-admin
        // Method ini akan berjalan sebelum semua pengecekan Gate & Policy lainnya
        Gate::before(function (User $user, $ability) {
            // Cek jika user memiliki role 'superadmin'
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}