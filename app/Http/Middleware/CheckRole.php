<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $userRole = Auth::user()->role;

        // Superadmin always has full access
        if ($userRole === 'superadmin') {
            return $next($request);
        }

        // Check if user's role matches any allowed role
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect editor to articles page with warning if attempting to access forbidden routes
        if ($userRole === 'editor') {
            return redirect()->route('admin.articles.index')
                ->with('error', 'Akses Ditolak. Account Role Editor hanya memiliki akses untuk mengelola Berita & Artikel.');
        }

        // Redirect helpdesk to inbox page with warning if attempting to access forbidden routes
        if ($userRole === 'helpdesk') {
            return redirect()->route('admin.inbox.index')
                ->with('error', 'Akses Ditolak. Account Role Helpdesk hanya memiliki akses untuk mengelola Interaksi (Inbox & FAQ).');
        }

        abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk mengedit bagian ini.');
    }
}
