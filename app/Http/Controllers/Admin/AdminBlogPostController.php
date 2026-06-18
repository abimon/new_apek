<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class AdminBlogPostController extends Controller
{
    public function index()
    {
        return view('admin.blog-posts.index', ['posts' => BlogPost::orderBy('created_at', 'desc')->get()]);
    }

    public function create()
    {
        return view('admin.blog-posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'content' => 'required|string',
            'featured_image' => 'nullable|url',
            'category' => 'required|string|max:120',
            'status' => 'required|in:draft,published',
        ]);

        BlogPost::create($validated);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts.edit', ['post' => $blogPost]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:blog_posts,slug,{$blogPost->id}",
            'content' => 'required|string',
            'featured_image' => 'nullable|url',
            'category' => 'required|string|max:120',
            'status' => 'required|in:draft,published',
        ]);

        $blogPost->update($validated);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post removed successfully.');
    }
}
