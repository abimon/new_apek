<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminBlogCommentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/blog/comments', [
            'comments' => BlogComment::query()->with('post:id,title,slug')->latest()->paginate(30),
        ]);
    }

    public function toggleApproval(BlogComment $comment): RedirectResponse
    {
        $comment->update(['approved_at' => $comment->approved_at ? null : now()]);

        return back()->with('success', $comment->approved_at ? 'Comment approved.' : 'Comment hidden.');
    }

    public function destroy(BlogComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
