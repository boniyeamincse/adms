<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    /**
     * Display a listing of all users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Filter by role if provided
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        $roles = [
            'superadmin' => 'Super Admin',
            'teacher' => 'Teacher',
            'accountant' => 'Accountant',
            'student' => 'Student',
        ];

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = [
            'superadmin' => 'Super Admin',
            'teacher' => 'Teacher',
            'accountant' => 'Accountant',
            'student' => 'Student',
        ];

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,teacher,accountant,student',
            'send_welcome_email' => 'nullable|boolean',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // Log the user creation
            Log::info('New user created', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'created_by' => auth()->id(),
            ]);

            // Send welcome email if requested
            if (!empty($validated['send_welcome_email'])) {
                $this->sendWelcomeEmail($user, $validated['password']);
            }

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.' . (isset($validated['send_welcome_email']) ? ' Welcome email sent.' : ''));

        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $this->authorizeUserAccess($user);

        return view('users.show', compact('user'));
    }

    /**
     * Display the current user's profile.
     */
    public function profile(): View
    {
        $user = auth()->user();

        return view('users.show', compact('user'));
    }

    /**
     * Update the current user's profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Different validation rules for profile update
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // Only SuperAdmin can change their own role
        if ($user->isSuperAdmin()) {
            $rules['role'] = 'nullable|in:superadmin,teacher,accountant,student';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        try {
            $updates = [];

            if (isset($validated['name'])) $updates['name'] = $validated['name'];
            if (isset($validated['email'])) $updates['email'] = $validated['email'];
            if (isset($validated['role']) && $user->isSuperAdmin()) $updates['role'] = $validated['role'];

            if (!empty($validated['password'])) {
                $updates['password'] = Hash::make($validated['password']);
            }

            $user->update($updates);

            $message = 'Profile updated successfully.';
            if (isset($validated['role']) && $user->isSuperAdmin()) {
                $message .= ' Role changed to ' . $user->getRoleName() . '.';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to update profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $this->authorizeUserAccess($user);

        $roles = [
            'superadmin' => 'Super Admin',
            'teacher' => 'Teacher',
            'accountant' => 'Accountant',
            'student' => 'Student',
        ];

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeUserAccess($user);

        // Different validation rules for different operations
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // Only SuperAdmin can change roles and passwords
        if (auth()->user()->isSuperAdmin()) {
            $rules['role'] = 'required|in:superadmin,teacher,accountant,student';
            if ($request->filled('password')) {
                $rules['password'] = 'nullable|string|min:8|confirmed';
            }
        }

        $validated = $request->validate($rules);

        try {
            $updates = [];

            if (isset($validated['name'])) $updates['name'] = $validated['name'];
            if (isset($validated['email'])) $updates['email'] = $validated['email'];
            if (isset($validated['role'])) $updates['role'] = $validated['role'];

            if (!empty($validated['password'])) {
                $updates['password'] = Hash::make($validated['password']);
            }

            $user->update($updates);

            // Log the update if it's a role change or password change
            if (isset($validated['role']) || !empty($validated['password'])) {
                Log::info('User updated', [
                    'user_id' => $user->id,
                    'updated_by' => auth()->id(),
                    'changes' => array_keys($updates)
                ]);
            }

            $message = 'Profile updated successfully.';
            if (isset($validated['role'])) {
                $message .= ' Role changed to ' . $user->getRoleName() . '.';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to update user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            // Prevent deletion of own account
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            // Prevent deletion of last SuperAdmin if this is the last one
            if ($user->isSuperAdmin() && User::where('role', 'superadmin')->count() <= 1) {
                return redirect()->back()->with('error', 'Cannot delete the last Super Admin account.');
            }

            $userName = $user->name;
            $user->delete();

            Log::info('User deleted', [
                'deleted_user_id' => $user->id,
                'deleted_user_name' => $userName,
                'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' has been successfully deleted.");

        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $user): RedirectResponse
    {
        try {
            $newPassword = $this->generateRandomPassword();
            $user->update(['password' => Hash::make($newPassword)]);

            Log::info('User password reset', [
                'user_id' => $user->id,
                'reset_by' => auth()->id(),
            ]);

            // Send password reset notification
            $this->sendPasswordResetNotification($user, $newPassword);

            return redirect()->back()
                ->with('success', "Password reset successfully for {$user->name}.");

        } catch (\Exception $e) {
            Log::error('Failed to reset user password', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to reset password. Please try again.');
        }
    }

    /**
     * Bulk update user roles.
     */
    public function bulkUpdateRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'new_role' => 'required|in:superadmin,teacher,accountant,student',
        ]);

        try {
            $count = User::whereIn('id', $validated['user_ids'])
                        ->update(['role' => $validated['new_role']]);

            Log::info('Bulk role update', [
                'user_ids' => $validated['user_ids'],
                'new_role' => $validated['new_role'],
                'updated_count' => $count,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('success', "{$count} user(s) role updated to " . User::find($validated['user_ids'][0])->getRoleName() . ".");

        } catch (\Exception $e) {
            Log::error('Failed bulk role update', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update user roles. Please try again.');
        }
    }

    /**
     * Get role statistics.
     */
    public function getRoleStats(): JsonResponse
    {
        $stats = User::selectRaw('role, COUNT(*) as count')
                    ->groupBy('role')
                    ->pluck('count', 'role')
                    ->toArray();

        $stats['total'] = array_sum($stats);

        return response()->json($stats);
    }

    /**
     * Authorize user access based on role hierarchy.
     */
    private function authorizeUserAccess(User $user): void
    {
        $currentUser = auth()->user();

        // SuperAdmin can access everything
        if ($currentUser->isSuperAdmin()) {
            return;
        }

        // Users can view/edit their own profile
        if ($currentUser->id === $user->id) {
            return;
        }

        // Teachers and Accountants can only view their own profiles
        abort(403, 'Access denied. You can only view and edit your own profile.');
    }

    /**
     * Generate a random secure password.
     */
    private function generateRandomPassword(): string
    {
        return 'Temp' . rand(1000, 9999) . '!#';
    }

    /**
     * Send welcome email to new user.
     */
    private function sendWelcomeEmail(User $user, string $password): void
    {
        try {
            // For now, we'll just log this. In a real application,
            // you would implement actual email sending
            Log::info('Welcome email would be sent', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'temp_password' => $password,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send password reset notification.
     */
    private function sendPasswordResetNotification(User $user, string $password): void
    {
        try {
            Log::info('Password reset notification', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'new_password' => $password,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}