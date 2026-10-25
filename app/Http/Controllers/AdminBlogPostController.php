<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AdminBlogPostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/blog/index', [
            'posts' => BlogPost::query()->with('categories')->withCount(['comments as pending_comments_count' => fn ($query) => $query->whereNull('approved_at')])->latest()->paginate(20),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/blog/form', ['post' => null]);
    }

    public function store(AdminBlogPostRequest $request): RedirectResponse
    {
        $data = $this->postData($request);
        $data['user_id'] = Auth::id();
        $data['cover_image'] = $this->storeCover($request);

        $post = BlogPost::create($data);
        $this->syncTaxonomy($post, $request);

        return to_route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $post): Response
    {
        $post->load(['categories:id,name', 'tags:id,name']);

        return Inertia::render('admin/blog/form', [
            'post' => [
                ...$post->toArray(),
                'status' => $post->status === 'scheduled' && $post->published_at?->isPast()
                    ? 'published'
                    : $post->status,
                'categories_text' => $post->categories->pluck('name')->implode(', '),
                'tags_text' => $post->tags->pluck('name')->implode(', '),
                'published_at' => $post->published_at?->format('Y-m-d\TH:i'),
            ],
        ]);
    }

    public function update(AdminBlogPostRequest $request, BlogPost $post): RedirectResponse
    {
        $oldCover = $post->cover_image;
        $data = $this->postData($request);
        $newCover = $this->storeCover($request);
        if ($newCover) {
            $data['cover_image'] = $newCover;
        }

        $post->update($data);
        $this->syncTaxonomy($post, $request);

        if ($newCover && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }

        return to_route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();

        return back()->with('success', 'Blog post removed.');
    }

    private function postData(AdminBlogPostRequest $request): array
    {
        $data = $request->safe()->except(['categories', 'tags', 'cover']);
        $data['published_at'] = match ($data['status']) {
            'draft' => null,
            'published' => now(),
            'scheduled' => $data['published_at'],
        };

        return $data;
    }

    private function syncTaxonomy(BlogPost $post, AdminBlogPostRequest $request): void
    {
        $post->categories()->sync($this->taxonomyIds(BlogCategory::class, $request->input('categories', '')));
        $post->tags()->sync($this->taxonomyIds(BlogTag::class, $request->input('tags', '')));
    }

    private function taxonomyIds(string $model, string $values): array
    {
        $names = collect(preg_split('/[,;\r\n]+/', $values) ?: [])
            ->map(fn (string $name) => trim($name))
            ->filter()
            ->unique(fn (string $name) => Str::lower($name))
            ->take(30);

        return $names->map(function (string $name) use ($model) {
            $slug = Str::slug($name);
            if ($slug === '') {
                return null;
            }

            return $model::firstOrCreate(['slug' => $slug], ['name' => $name])->id;
        })->filter()->all();
    }

    private function storeCover(AdminBlogPostRequest $request): ?string
    {
        if (! $request->hasFile('cover')) {
            return null;
        }

        $path = $request->file('cover')->store('blog/covers', 'public');
        if (! $path) {
            throw ValidationException::withMessages(['cover' => 'The cover image could not be saved. Please try again.']);
        }

        return $path;
    }
}
