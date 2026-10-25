<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogCommentRequest;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class PublicBlogController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('q')->trim()->toString();
        $category = $request->string('category')->toString();
        $tag = $request->string('tag')->toString();

        $posts = BlogPost::query()->visible()
            ->with(['author:id,name', 'categories:id,name,slug', 'tags:id,name,slug'])
            ->when($search, fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->when($category, fn ($query) => $query->whereHas('categories', fn ($categories) => $categories->where('slug', $category)))
            ->when($tag, fn ($query) => $query->whereHas('tags', fn ($tags) => $tags->where('slug', $tag)))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('blog/index', [
            'posts' => $posts,
            'categories' => BlogCategory::query()->whereHas('posts', fn ($query) => $query->visible())->orderBy('name')->get(['id', 'name', 'slug']),
            'tags' => BlogTag::query()->whereHas('posts', fn ($query) => $query->visible())->orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => ['q' => $search, 'category' => $category, 'tag' => $tag],
            'canonicalUrl' => URL::route('blog.index'),
        ]);
    }

    public function show(BlogPost $post): Response
    {
        abort_unless(in_array($post->status, ['published', 'scheduled'], true) && $post->published_at?->isPast(), 404);

        $post->load(['author:id,name', 'categories:id,name,slug', 'tags:id,name,slug']);

        return Inertia::render('blog/show', [
            'post' => $post,
            'comments' => $post->comments()->whereNotNull('approved_at')->latest()->get(['id', 'name', 'body', 'created_at']),
            'canonicalUrl' => URL::route('blog.show', $post->slug),
        ]);
    }

    public function comment(StoreBlogCommentRequest $request, BlogPost $post): RedirectResponse
    {
        abort_unless(in_array($post->status, ['published', 'scheduled'], true) && $post->published_at?->isPast(), 404);

        BlogComment::create([
            ...$request->validated(),
            'blog_post_id' => $post->id,
        ]);

        return back()->with('success', 'Thanks for joining the conversation. Your comment will appear after review.');
    }
}
