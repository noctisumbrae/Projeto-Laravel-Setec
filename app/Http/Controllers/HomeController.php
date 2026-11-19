<?php

namespace App\Http\Controllers;

use App\Models\Edicao;
use App\Models\Inscricao;
use Illuminate\Support\Facades\Auth;

/** CONTROLLER - Página inicial com os dados da edição vigente. */
class HomeController extends Controller
{
    public function index()
    {
        $edicao = Edicao::vigente();
        $inscricao = null;

        if ($edicao && Auth::check()) {
            $inscricao = Inscricao::where('user_id', Auth::id())
                ->where('edicao_id', $edicao->id)
                ->first();
        }

        return view('home', compact('edicao', 'inscricao'));
    }
}