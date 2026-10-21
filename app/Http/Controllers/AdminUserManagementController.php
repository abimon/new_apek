<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserManagementController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/users/index', [
            'users' => User::query()
                ->orderBy('name')
                ->paginate(30, ['id', 'name', 'email', 'role', 'is_active', 'portfolio_enabled', 'portfolio_slug', 'created_at']),
        ]);
    }

    public function updateAccess(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $isActive = (bool) $data['is_active'];
        if (! $isActive && $request->user()->is($user)) {
            throw ValidationException::withMessages([
                'access' => 'You cannot revoke access from your own account.',
            ]);
        }

        DB::transaction(function () use ($user, $isActive): void {
            if (! $isActive && $user->role === 'superadmin' && $user->is_active) {
                $activeSuperadmins = User::query()
                    ->where('role', 'superadmin')
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->count();

                if ($activeSuperadmins <= 1) {
                    throw ValidationException::withMessages([
                        'access' => 'The last active superadmin account cannot be disabled.',
                    ]);
                }
            }

            $user->update(['is_active' => $isActive]);
        });

        request()->attributes->set('audit_subject', $user);

        return back()->with('success', $isActive ? 'User access enabled.' : 'User access revoked.');
    }
}
