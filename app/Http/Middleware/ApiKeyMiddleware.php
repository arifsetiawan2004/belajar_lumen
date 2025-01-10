<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Apikey;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $apiKey = ApiKey::where('api_key', $apiKey)->first();
        if (!$apiKey)
            return response('Unauthorized!', 401);

        return $next($request);
    }
}
