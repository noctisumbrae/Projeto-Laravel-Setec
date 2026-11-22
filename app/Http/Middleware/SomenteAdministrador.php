<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SomenteAdministrador
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (!Auth::check()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        if (Auth::user()->perfil !== 'administrador') {
            abort(403, 'Você não tem permissão para acessar os relatórios.');
        }

        return $next($request);
    }
}
