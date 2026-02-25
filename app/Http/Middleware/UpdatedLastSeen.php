<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class UpdatedLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (auth()->user()->last_seen_at === null || auth()->user()->last_seen_at->lt(Carbon::now()->subMinutes(2))) {
                auth()->user()->last_seen_at = now();
                auth()->user()->save();
                }        } 
        return $next($request);
    
}
}