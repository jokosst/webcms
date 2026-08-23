<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 10;
        }

        $query = Article::with(['category', 'user'])->withCount('allComments');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest('created_at')->paginate($perPage)->withQueryString();

        return view('admin.articles.index', compact('articles', 'search', 'perPage'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'article')->get();

        return view('admin.articles.form', [
            'article' => new Article(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image_file' => 'nullable|image|max:3072',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'allow_comments' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['user_id'] = Auth::id() ?? 1;
        $validated['allow_comments'] = $request->boolean('allow_comments');

        if ($request->hasFile('featured_image_file')) {
            $validated['featured_image'] = $request->file('featured_image_file')->store('articles', 'public');
        }

        if ($request->filled('published_at')) {
            $validated['published_at'] = $request->input('published_at');
        } elseif ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        } else {
            $validated['published_at'] = null;
        }

        $validated['seo_meta'] = [
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ];

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function show(Article $article): View
    {
        $article->load(['category', 'user', 'comments.replies']);

        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        $categories = Category::where('type', 'article')->get();

        return view('admin.articles.form', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image_file' => 'nullable|image|max:3072',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'allow_comments' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['allow_comments'] = $request->boolean('allow_comments');

        if ($request->hasFile('featured_image_file')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image_file')->store('articles', 'public');
        }

        if ($request->filled('published_at')) {
            $validated['published_at'] = $request->input('published_at');
        } elseif ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $validated['seo_meta'] = [
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ];

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function replyComment(Request $request, Article $article, ArticleComment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        ArticleComment::create([
            'article_id' => $article->id,
            'parent_id' => $comment->id,
            'name' => Auth::user()->name ?? 'Admin',
            'email' => Auth::user()->email ?? 'admin@example.com',
            'comment' => $validated['comment'],
            'is_admin_reply' => true,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Balasan komentar berhasil dikirim.');
    }

    public function deleteComment(ArticleComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
