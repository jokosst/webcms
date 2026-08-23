<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = ArticleComment::with('article')
            ->whereNull('parent_id')
            ->latest()
            ->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    public function reply(Request $request, ArticleComment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        ArticleComment::create([
            'article_id' => $comment->article_id,
            'parent_id' => $comment->id,
            'name' => Auth::user()->name ?? 'Admin',
            'email' => Auth::user()->email ?? 'admin@example.com',
            'comment' => $validated['comment'],
            'is_admin_reply' => true,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Balasan komentar berhasil dikirim.');
    }

    public function destroy(ArticleComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
