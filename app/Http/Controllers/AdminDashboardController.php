<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsLog;
use App\Models\ContactInquiry;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $start = now()->subDays(6)->startOfDay();
        $views = AnalyticsLog::query()->where('visited_at', '>=', $start)->selectRaw('DATE(visited_at) as day, COUNT(*) as views, COUNT(DISTINCT session_hash) as visitors')->groupBy('day')->orderBy('day')->get();

        return Inertia::render('dashboard', [
            'stats' => [
                'projects' => Project::count(),
                'published' => Project::where('is_published', true)->count(),
                'unread' => ContactInquiry::whereNull('read_at')->count(),
                'views' => AnalyticsLog::where('visited_at', '>=', now()->subDays(30))->count(),
            ],
            'trend' => $views,
            'inquiries' => ContactInquiry::latest()->limit(5)->get(['id', 'name', 'email', 'service', 'message', 'read_at', 'created_at']),
            'projects' => Project::with('categories')->latest()->limit(5)->get(),
        ]);
    }

    public function markInquiryRead(ContactInquiry $inquiry): \Illuminate\Http\RedirectResponse
    {
        $inquiry->update(['read_at' => now()]);

        return back();
    }
}