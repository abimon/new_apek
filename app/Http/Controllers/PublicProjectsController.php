<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class PublicProjectsController extends Controller
{
    public function index(Request $request): Response
    {
        $category = $request->string('category')->toString();
        $projects = Project::query()
            ->with(['categories', 'techStacks'])
            ->where('is_published', true)
            ->when($category, fn ($query) => $query->whereHas('categories', fn ($categories) => $categories->where('slug', $category)))
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('projects/index', [
            'projects' => $projects,
            'categories' => Category::query()->whereHas('projects', fn ($query) => $query->where('is_published', true))
                ->orderBy('name')->get(['id', 'name', 'slug']),
            'activeCategory' => $category,
            'canonicalUrl' => URL::route('projects.index'),
        ]);
    }
}
