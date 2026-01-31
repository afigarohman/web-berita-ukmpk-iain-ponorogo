<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $date = Carbon::now()->toDateString();

        // Cek apakah IP ini sudah berkunjung hari ini (agar tidak double count per reload)
        $exists = DB::table('visits')
                    ->where('ip_address', $ip)
                    ->where('visit_date', $date)
                    ->exists();

        if (!$exists) {
            DB::table('visits')->insert([
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'visit_date' => $date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}