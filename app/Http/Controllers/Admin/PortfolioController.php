<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $portfolios = Portfolio::with('category')->orderBy('sort_order', 'asc')->latest('completion_date')->get();

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'portfolio')->get();

        return view('admin.portfolios.form', [
            'portfolio' => new Portfolio(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'client_name' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:3072',
            'short_description' => 'nullable|string',
            'content' => 'nullable|string',
            'project_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['sort_order'] = (int) ($request->input('sort_order', 0));
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('portfolios', 'public');
        }

        Portfolio::create($validated);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio proyek berhasil ditambahkan.');
    }

    public function edit(Portfolio $portfolio): View
    {
        $categories = Category::where('type', 'portfolio')->get();

        return view('admin.portfolios.form', [
            'portfolio' => $portfolio,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'client_name' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:3072',
            'short_description' => 'nullable|string',
            'content' => 'nullable|string',
            'project_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['sort_order'] = (int) ($request->input('sort_order', 0));
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($portfolio->thumbnail_path) {
                Storage::disk('public')->delete($portfolio->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('portfolios', 'public');
        }

        $portfolio->update($validated);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio proyek berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        if ($portfolio->thumbnail_path) {
            Storage::disk('public')->delete($portfolio->thumbnail_path);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio proyek berhasil dihapus.');
    }
}
