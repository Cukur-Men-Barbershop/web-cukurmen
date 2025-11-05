# Middleware Documentation

This directory contains custom middleware for role-based access control in Laravel 11+.

## Available Middleware

### isAdmin
- **Purpose**: Ensures that only authenticated users with admin privileges can access the route
- **Usage**: Use this middleware for admin-only features like user management, system settings, etc.
- **Implementation**: Checks if the authenticated user has `role` property set to `'admin'` or uses the `isAdmin()` method from the User model

### isUser
- **Purpose**: Ensures that only authenticated users can access the route
- **Usage**: Use this middleware for regular user features like profile access, bookings, etc.
- **Implementation**: Simply checks if the user is authenticated

## Registration

Middleware is registered in `bootstrap/app.php` using the alias method, which is the standard approach for Laravel 11+.

## How to Use

### In Route Definition:
```php
// For admin-only routes
Route::middleware(['isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
});

// For user-only routes
Route::middleware(['isUser'])->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::get('/user/bookings', [UserController::class, 'bookings']);
});

// For single routes
Route::get('/admin/settings', [AdminController::class, 'settings'])->middleware('isAdmin');
Route::get('/user/history', [UserController::class, 'history'])->middleware('isUser');
```

### In Controller Constructor:
```php
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }
}
```

## Implementation Details
- **isAdmin Middleware**: Checks if `$user->role === 'admin'` or if `$user->isAdmin()` method returns true
- **isUser Middleware**: Only checks if the user is authenticated via `Auth::check()`

## Testing
Two test routes have been added to verify middleware functionality:
- `/test/admin` - requires admin access
- `/test/user` - requires user authentication

## Notes
- Both middleware return JSON responses with appropriate error messages and HTTP status codes (401 for unauthenticated, 403 for unauthorized)
- The `isAdmin` middleware is compatible with the existing User model that uses role-based access
- The `isUser` middleware can be customized further if you need more complex role checking
- Middleware has been registered in `bootstrap/app.php` using the alias method (for Laravel 11+) and is ready for use