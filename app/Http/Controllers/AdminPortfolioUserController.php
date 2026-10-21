<?php

namespace App\Http\Controllers;

use App\Mail\PortfolioAccountCredentials;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminPortfolioUserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/portfolio-users/index', [
            'users' => User::query()
                ->where('portfolio_enabled', true)
                ->withCount('portfolioProjects')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'portfolio_slug', 'portfolio_is_public', 'created_at']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);
        $temporaryPassword = Str::password(24, symbols: true);
        $user = DB::transaction(function () use ($data, $temporaryPassword): User {
            do {
                $slug = Str::lower(Str::random(32));
            } while (User::query()->where('portfolio_slug', $slug)->exists());

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $temporaryPassword,
                'role' => 'worker',
                'portfolio_enabled' => true,
                'portfolio_slug' => $slug,
                'force_password_change' => true,
            ]);

            Mail::to($user->email)->send(new PortfolioAccountCredentials($user, $temporaryPassword));

            return $user;
        });
        request()->attributes->set('audit_subject', $user);

        return back()->with('success', 'Portfolio account created and login instructions emailed to the user.');
    }
}
