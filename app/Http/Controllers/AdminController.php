<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display and filter system users.
     */
    public function users(Request $request)
    {
        // Start with a query for all users.
        $query = User::query();

        // Filter by account status when a status is selected.
        $allowedStatuses = [
            'pending',
            'approved',
            'rejected',
            'suspended',
        ];

        if (
            $request->filled('status') &&
            in_array($request->input('status'), $allowedStatuses, true)
        ) {
            $query->where('status', $request->input('status'));
        }

        // Search by name, email, or position.
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%');
            });
        }

        // Return only users matching the selected filters.
        $users = $query->latest()->get();

        // Calculate overall account counts, independent of filters.
        $pendingUsers = User::where('status', 'pending')->count();
        $approvedUsers = User::where('status', 'approved')->count();
        $rejectedUsers = User::where('status', 'rejected')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();

        return view('admin.users', compact(
            'users',
            'pendingUsers',
            'approvedUsers',
            'rejectedUsers',
            'suspendedUsers'
        ));
    }

    /**
     * Approve a pending user and assign a system role.
     */
    public function approve(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => [
                'required',
                'string',
                'in:health_worker,data_officer,administrator',
            ],
        ]);

        // Assign the role and status directly.
        $user->role = $validated['role'];
        $user->status = 'approved';
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                $user->name . ' has been successfully approved.'
            );
    }

    /**
     * Reject a user registration.
     */
    public function reject(User $user)
    {
        $user->status = 'rejected';
        $user->role = null;
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                $user->name . ' has been rejected.'
            );
    }

    /**
     * Suspend an approved user.
     */
    public function suspend(User $user)
    {
        // Prevent an administrator from suspending their own account.
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users')
                ->withErrors([
                    'admin' => 'You cannot suspend your own administrator account.',
                ]);
        }

        $user->status = 'suspended';
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                $user->name . ' has been suspended.'
            );
    }

    /**
     * Reactivate a suspended or rejected user.
     */
    public function activate(User $user)
    {
        $user->status = 'approved';
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                $user->name . ' has been activated.'
            );
    }

    /**
     * Update a user's system role.
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => [
                'required',
                'string',
                'in:health_worker,data_officer,administrator',
            ],
        ]);

        // Prevent removing the current administrator's own admin role.
        if (
            $user->id === auth()->id() &&
            $validated['role'] !== 'administrator'
        ) {
            return redirect()
                ->route('admin.users')
                ->withErrors([
                    'admin' => 'You cannot remove the administrator role from your own account.',
                ]);
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                $user->name . '\'s role has been successfully updated.'
            );
    }
}
