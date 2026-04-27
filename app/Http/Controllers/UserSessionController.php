<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class UserSessionController extends Controller
{
    public function index()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name', 'users.jabatan')
            ->get()
            ->map(function ($session) {

                $agent = new Agent();
                $agent->setUserAgent($session->user_agent);

                $session->browser = $agent->browser();
                $session->platform = $agent->platform();

                $session->device = match (true) {
                    $agent->isDesktop() => 'Desktop',
                    $agent->isTablet() => 'Tablet',
                    $agent->isMobile() => 'Mobile',
                    default => 'Unknown',
                };

                return $session;
            });

        return view('layouts.sessions.index', compact('sessions'));
    }
}
