<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Closure;
use Illuminate\Http\Request;

class TrackPageView     //LUST change for count visits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if it's (Check .env for ADMIN_IP)
        if ($request->ip() === env('ADMIN_IP')) {
            return $next($request); // Skip counting logic immediately
        }

        // Check Bot status
        $userAgent = $request->header('User-Agent');
        $userAgentLower = strtolower($userAgent);
        
        // Determine if it's a bot OR if the agent is totally empty
        $isBot = !$userAgent || 
                str_contains($userAgentLower, 'bot') || 
                str_contains($userAgentLower, 'spider') || 
                str_contains($userAgentLower, 'crawler');

        // Cache the 'stop' status for 60 minutes
        $isStopped = Cache::remember('pageview_stop_status', 3600, function () {
            return PageView::orderBy('id', 'asc')->where('url', 'STOP')->exists();
        });
        $sessionKey = 'viewed_url_' . md5($request->fullUrl());     //add to prevent a single user from inflating the count by refreshing, check if they have already viewed the page in their current session

        // 4. Combined Logic
        if (!$isBot && 
            !$isStopped && 
            $request->isMethod('get') && 
            !Session::has($sessionKey)) {
            PageView::create([
                'url' => $request->fullUrl(),
                'session_id' => Session::getId(),
                'ip_address' => $request->ip(),
            ]);
        }

        return $next($request);
    }
}
