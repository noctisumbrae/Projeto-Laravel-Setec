<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\InscricaoPolicy;
use App\Models\Inscricao;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::inscricao(function (User $user, $inscricao) {
            return (new InscricaoPolicy($user))->view($user, $inscricao);
        });
    }
}