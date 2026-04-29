<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;

class UserSessionController extends Controller
{
    public function index()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.*',
                'users.name',
                'users.jabatan'
            )
            ->whereNotNull('sessions.user_id')
            ->get()
            ->map(function ($s) {

                $agent = new Agent();
                $agent->setUserAgent($s->user_agent);

                // Device
                $s->device = match (true) {
                    $agent->isDesktop() => 'desktop',
                    $agent->isTablet() => 'tablet',
                    $agent->isMobile() => 'mobile',
                    default => 'unknown',
                };

                $s->browser = $agent->browser();
                $s->platform = $agent->platform();

                // Status
                $last = Carbon::createFromTimestamp($s->last_activity);
                $diff = now()->diffInMinutes($last);

                $s->diff = $diff;

                $s->status = match (true) {
                    $diff < 5 => 'online',
                    $diff < 30 => 'idle',
                    default => 'away',
                };

                $s->time_label = match (true) {
                    $diff < 1 => 'Aktif sekarang',
                    $diff < 60 => $diff . ' menit lalu',
                    default => floor($diff / 60) . ' jam lalu',
                };

                return $s;
            });

        return view('layouts.sessions.index', compact('sessions'));
    }
}
