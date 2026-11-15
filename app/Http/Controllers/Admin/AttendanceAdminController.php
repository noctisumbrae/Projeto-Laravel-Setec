<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceAdminController extends Controller
{
    public function index()
    {
        $activities = Activity::withCount('attendances')->orderBy('starts_at')->get();
        return view('admin.attendance.index', compact('activities'));
    }

    public function byActivity(Activity $activity)
    {
        $attendances = Attendance::with('participant')
            ->where('activity_id', $activity->id)
            ->orderBy('registered_at')
            ->get();

        $presentIds = $attendances->pluck('participant_id')->toArray();
        
        $absent = DB::table('activity_participant')
            ->join('users', 'users.id', '=', 'activity_participant.participant_id')
            ->where('activity_id', $activity->id)
            ->where('status', 'confirmed')
            ->whereNotIn('participant_id', $presentIds)
            ->select('users.id', 'users.name', 'users.email')
            ->get();

        return view('admin.attendance.by-activity', compact('activity', 'attendances', 'absent'));
    }

    // NOVO MÉTODO: Registrar Presença Manual
    public function storeManual(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'participant_id' => 'required|exists:users,id',
            'correction_justification' => 'required|string|min:5',
        ]);

        Attendance::create([
            'edition_id'     => $activity->edition_id,
            'activity_id'    => $activity->id,
            'participant_id' => $data['participant_id'],
            'registered_at'  => now(),
            'is_manual'      => true,
            'correction_justification' => $data['correction_justification'],
        ]);

        return back()->with('success', 'Presença manual registrada com sucesso!');
    }
}