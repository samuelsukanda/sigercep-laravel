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

                // DEVICE
                $s->device = match (true) {
                    $agent->isDesktop() => 'desktop',
                    $agent->isTablet() => 'tablet',
                    $agent->isMobile() => 'mobile',
                    default => 'unknown',
                };

                $s->browser = $agent->browser();
                $s->platform = $agent->platform();

                if (!$s->last_activity) {
                    $timestamp = now()->subYears(1)->timestamp;
                } else {
                    $timestamp = (int) $s->last_activity;
                }

                $diff = now()->timestamp - $timestamp;

                // STATUS
                if ($diff < 120) {
                    $s->status = 'online';
                } elseif ($diff < 600) {
                    $s->status = 'idle';
                } else {
                    $s->status = 'offline';
                }

                $s->last_activity_at = date('d/m/Y H:i:s', $timestamp);

                $s->time_label = Carbon::createFromTimestamp($timestamp)->diffForHumans();

                return $s;
            });

        return view('layouts.sessions.index', compact('sessions'));
    }
}