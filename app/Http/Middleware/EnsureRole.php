<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return $this->handleUnauthenticated($request);
        }

        $user = auth()->user();

        // SuperAdmin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user role matches any of the required roles
        if (!in_array($user->role, $roles)) {
            return $this->handleUnauthorized($request, $user, $roles);
        }

        // Log successful access for audit purposes
        $this->logAccess($request, $user, 'granted');

        return $next($request);
    }

    /**
     * Handle unauthenticated requests.
     */
    private function handleUnauthenticated(Request $request): RedirectResponse
    {
        Log::info('Unauthenticated access attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Authentication required'], 401);
        }

        return redirect()->route('login')->with('error', 'You must be logged in to access this resource.');
    }

    /**
     * Handle unauthorized access attempts.
     */
    private function handleUnauthorized(Request $request, $user, array $roles): Response
    {
        Log::warning('Unauthorized role access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Access denied. Insufficient privileges.',
                'required_roles' => $roles,
                'user_role' => $user->role
            ], 403);
        }

        // Provide specific error messages based on user role
        $message = $this->getRoleBasedErrorMessage($user, $roles);

        return redirect()->back()->with('error', $message);
    }

    /**
     * Get role-specific error messages.
     */
    private function getRoleBasedErrorMessage($user, array $roles): string
    {
        if ($user->isTeacher()) {
            if (in_array('superadmin', $roles)) {
                return 'Only Super Administrators can access this feature.';
            }
            if (in_array('accountant', $roles)) {
                return 'This feature is restricted to Accountants only.';
            }
        }

        if ($user->isAccountant()) {
            if (in_array('superadmin', $roles)) {
                return 'Only Super Administrators can access this feature.';
            }
            if (in_array('teacher', $roles)) {
                return 'This feature is restricted to Teachers only.';
            }
        }

        if ($user->isStudent()) {
            return 'This feature requires elevated permissions. Contact your administrator.';
        }

        return 'Access denied. You do not have permission to access this resource.';
    }

    /**
     * Log successful access for audit purposes.
     */
    private function logAccess(Request $request, $user, string $status): void
    {
        Log::info('Role-based access ' . $status, [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now(),
        ]);
    }
}
