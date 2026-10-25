<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactInquiryRequest;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\ContactInquiry;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class PublicSiteController extends Controller
{
    public function home(Request $request): Response
    {
        $category = $request->string('category')->toString();
        $projects = Project::query()->with(['categories', 'techStacks'])->where('is_published', true)->when($category, fn ($query) => $query->whereHas('categories', fn ($categories) => $categories->where('slug', $category)))->latest()->get();

        return Inertia::render('welcome', [
            'projects' => $projects,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'activeCategory' => $category,
            'canonicalUrl' => URL::route('home'),
        ]);
    }

    public function inquiry(StoreContactInquiryRequest $request): RedirectResponse
    {
        ContactInquiry::create($request->validated());

        return back()->with('success', 'Thanks for reaching out. We will be in touch within one business day.');
    }

    public function about(): Response
    {
        return Inertia::render('about', [
            'canonicalUrl' => URL::route('about'),
        ]);
    }

    public function services(): Response
    {
        return Inertia::render('services', [
            'canonicalUrl' => URL::route('services'),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('contact', [
            'canonicalUrl' => URL::route('contact'),
        ]);
    }

    public function project(Project $project): Response
    {
        abort_unless($project->is_published, 404);

        return Inertia::render('projects/show', [
            'project' => $project->load(['categories', 'techStacks']),
            'relatedProjects' => Project::query()
                ->with(['categories', 'techStacks'])
                ->where('is_published', true)
                ->where('id', '!=', $project->id)
                ->latest()
                ->limit(3)
                ->get(),
            'canonicalUrl' => URL::route('projects.show', $project),
        ]);
    }

    public function robots(): HttpResponse
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /dashboard\nDisallow: /login\nDisallow: /register\n\nSitemap: ".URL::route('sitemap')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): HttpResponse
    {
        $urls = collect([
            ['loc' => URL::route('home'), 'lastmod' => null],
            ['loc' => URL::route('projects.index'), 'lastmod' => null],
            ['loc' => URL::route('blog.index'), 'lastmod' => null],
            ['loc' => URL::route('about'), 'lastmod' => null],
            ['loc' => URL::route('services'), 'lastmod' => null],
            ['loc' => URL::route('contact'), 'lastmod' => null],
        ])->concat(Project::query()->where('is_published', true)->get(['slug', 'updated_at'])->map(fn (Project $project) => [
            'loc' => URL::route('projects.show', $project),
            'lastmod' => $project->updated_at?->toDateString(),
        ]))->concat(User::query()
            ->where('is_active', true)
            ->where('portfolio_enabled', true)
            ->where('portfolio_is_public', true)
            ->whereNotNull('portfolio_slug')
            ->get(['portfolio_slug', 'updated_at'])
            ->map(fn (User $user) => [
                'loc' => URL::route('portfolio.public', $user->portfolio_slug),
                'lastmod' => $user->updated_at?->toDateString(),
            ]))->concat(BlogPost::query()->visible()->get(['slug', 'updated_at'])->map(fn (BlogPost $post) => [
                'loc' => URL::route('blog.show', $post->slug),
                'lastmod' => $post->updated_at?->toDateString(),
            ]));

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
