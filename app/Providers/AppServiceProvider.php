<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\InscricaoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('inscricao', function (User $user, $inscricao) {
            return (new InscricaoPolicy($user))->view($user, $inscricao);
        });
    }
}