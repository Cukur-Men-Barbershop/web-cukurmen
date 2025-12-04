<?php

namespace Tests\Unit\Auth;

use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_middleware_allows_admin_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $request = Mockery::mock(Request::class);
        $next = function ($request) {
            return response('success');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($admin);

        $middleware = new isAdmin();
        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
    }

    public function test_is_admin_middleware_blocks_non_admin_user()
    {
        $user = User::factory()->create(['role' => 'user']);
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('expectsJson')->andReturn(true);
        $next = function ($request) {
            return response('success');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);

        $middleware = new isAdmin();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('{"error":"Admin access required"}', $response->getContent());
    }

    public function test_is_admin_middleware_blocks_unauthenticated_user()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('expectsJson')->andReturn(true);
        $next = function ($request) {
            return response('success');
        };

        Auth::shouldReceive('check')->andReturn(false);

        $middleware = new isAdmin();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('{"error":"Unauthorized"}', $response->getContent());
    }

    public function test_is_user_middleware_allows_authenticated_user()
    {
        $user = User::factory()->create(['role' => 'user']);
        $request = Mockery::mock(Request::class);
        $next = function ($request) {
            return response('success');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);

        $middleware = new isUser();
        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
    }

    public function test_is_user_middleware_allows_authenticated_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $request = Mockery::mock(Request::class);
        $next = function ($request) {
            return response('success');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($admin);

        $middleware = new isUser();
        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
    }

    public function test_is_user_middleware_blocks_unauthenticated_user()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('expectsJson')->andReturn(true);
        $next = function ($request) {
            return response('success');
        };

        Auth::shouldReceive('check')->andReturn(false);

        $middleware = new isUser();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('{"error":"Unauthorized"}', $response->getContent());
    }
}