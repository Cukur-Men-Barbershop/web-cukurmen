<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        
        // This middleware ensures the user is authenticated
        // You can add additional role checks if needed based on your user model
        // For example, if you need to ensure the user is not an admin:
        // if ($user->is_admin ?? false) {
        //     return response()->json(['error' => 'Regular user access required'], 403);
        // }
        
        return $next($request);
    }
}
