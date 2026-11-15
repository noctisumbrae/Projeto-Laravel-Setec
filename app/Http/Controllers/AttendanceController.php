<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Exibe o formulário de presença
    public function showForm($token)
    {
        $activity = Activity::where('qr_token', $token)->firstOrFail();
        return view('attendance.form', compact('activity', 'token'));
    }

    // Salva a presença
    public function register(Request $request, $token)
    {
        $activity = Activity::where('qr_token', $token)->firstOrFail();
        
        // NOTA: Como a Equipe 2 ainda não entregou o login, vamos usar o primeiro usuário do banco para testar.
        // Mais para frente, isso será substituído por: $user = auth()->user();
        $user = User::first(); 

        if (!$user) {
            return back()->with('error', 'Nenhum usuário cadastrado para simular a presença.');
        }

        // Verifica se já registrou presença
        $exists = Attendance::where('activity_id', $activity->id)
            ->where('participant_id', $user->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Sua presença já foi registrada nesta atividade!');
        }

        // Salva no banco
        Attendance::create([
            'edition_id'     => $activity->edition_id,
            'activity_id'    => $activity->id,
            'participant_id' => $user->id,
            'registered_at'  => now(),
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return back()->with('success', 'Presença registrada com sucesso!');
    }
}