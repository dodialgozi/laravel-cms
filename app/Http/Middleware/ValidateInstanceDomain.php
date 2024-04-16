<?php

namespace App\Http\Middleware;

use App\Models\Instance;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateInstanceDomain
{
    /**
     * Handle an incoming request.
     *
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $instances = Instance::all();
        $instance = $instances->firstWhere('instance_domain', $request->route('domain'));
        if (!$instance) {
            return response()->json(['message' => 'Instance not found'], 404);
        }
        return $next($request);
    }
}
