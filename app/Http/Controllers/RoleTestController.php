<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleTestController extends Controller
{
    public function __construct()
    {
        // Middleware is applied individually to each method at the route level
        // adminOnly requires 'isAdmin' middleware
        // userOnly requires 'isUser' middleware
    }
    
    public function adminOnly(Request $request)
    {
        return response()->json([
            'message' => 'This is an admin-only route',
            'user' => $request->user(),
            'role' => 'admin'
        ]);
    }

    public function userOnly(Request $request)
    {
        return response()->json([
            'message' => 'This is a user-only route', 
            'user' => $request->user(),
            'role' => 'user'
        ]);
    }
}