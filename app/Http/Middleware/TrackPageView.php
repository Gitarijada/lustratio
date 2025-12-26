<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;

class TrackPageView     //MY change for count visits
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
        PageView::create([
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
        ]);

        return $next($request);
    }
}
