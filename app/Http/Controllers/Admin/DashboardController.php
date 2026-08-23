<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\InboxLead;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_services' => Service::count(),
            'total_portfolios' => Portfolio::count(),
            'total_articles' => Article::count(),
            'unread_leads' => InboxLead::where('status', 'unread')->count(),
            'total_comments' => ArticleComment::count(),
        ];

        $recent_leads = InboxLead::latest()->take(5)->get();
        $recent_comments = ArticleComment::with('article')->latest()->take(5)->get();
        $top_commented_articles = Article::withCount('allComments')
            ->orderBy('all_comments_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_leads',
            'recent_comments',
            'top_commented_articles'
        ));
    }
}
