<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;


class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            if ($token->expires_at && Carbon::now()->greaterThan($token->expires_at)) {
                $token->delete();
                return response()->json(['message' => 'Token expired'], 401);
            }
        }

        return $next($request);
    }
}

