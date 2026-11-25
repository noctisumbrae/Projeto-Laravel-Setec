<?php

namespace App\Http\Controllers;

use App\Models\Edicao;

class ProgramacaoController extends Controller
{
    public function index()
    {
        $edicao = Edicao::vigente();
        
        return view('programacao.index', compact('edicao'));
    }

    public function show($atividadeId)
    {
        $atividade = App\Models\Atividade::find($atividadeId);
        
        if (!$atividade) {
            abort(404);
        }
        
        return view('programacao.show', compact('atividade'));
    }
