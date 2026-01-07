<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Episode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_instructors' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'instructor');
            })->count(),
            'total_courses' => Course::count(),
            'total_episodes' => Episode::count(),
            'total_categories' => Category::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }

    /**
     * Display all users.
     */
    public function users()
    {
        $users = User::with('roles')
            ->select('id', 'name', 'email', 'profile_photo_url', 'created_at')
            ->get()
            ->append('role_names');

        return Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Display all instructors.
     */
    public function instructors()
    {
        $instructors = User::whereHas('roles', function ($query) {
            $query->where('slug', 'instructor');
        })->with('course')
            ->select('id', 'name', 'email', 'profile_photo_url', 'is_active_instructor', 'created_at')
            ->get()
            ->map(function ($user) {
                // Add status based on approval and calculate total students
                $user->status = $user->is_active_instructor ? 'active' : 'pending';
                $user->courses_count = $user->course->count();
                $user->total_students = 0; // Will be calculated from enrollments
                return $user;
            });

        $stats = [
            'total' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'instructor');
            })->count(),
            'active' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'instructor');
            })->where('is_active_instructor', true)->count(),
            'pending' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'instructor');
            })->where('is_active_instructor', false)->count(),
            'total_courses' => Course::count(),
        ];

        return Inertia::render('Admin/Instructors', [
            'instructors' => $instructors,
            'stats' => $stats,
        ]);
    }

    /**
     * Approve an instructor.
     */
    public function approveInstructor(User $user)
    {
        $user->update(['is_active_instructor' => true]);
        
        return redirect()->back()->with('success', 'Instructor approved successfully.');
    }

    /**
     * Revoke instructor status.
     */
    public function revokeInstructor(User $user)
    {
        $user->update(['is_active_instructor' => false]);
        
        return redirect()->back()->with('success', 'Instructor status revoked.');
    }

    /**
     * Display all roles.
     */
    public function roles()
    {
        $roles = Role::withCount('users')->get();

        return Inertia::render('Admin/Roles', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a new role.
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:500',
        ]);

        Role::create($validated);

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    /**
     * Update a role.
     */
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $role->update($validated);

        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    /**
     * Delete a role.
     */
    public function destroyRole(Role $role)
    {
        if ($role->slug === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin role.');
        }

        $role->users()->detach();
        $role->delete();

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }

    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,slug',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->assignRole($validated['role']);

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,slug',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->removeRole($validated['role']);

        return redirect()->back()->with('success', 'Role removed successfully.');
    }
}

