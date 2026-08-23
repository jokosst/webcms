<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroBannerController extends Controller
{
    public function index(): View
    {
        $banners = HeroBanner::orderBy('sort_order', 'asc')->get();

        return view('admin.hero_banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.hero_banners.form', ['banner' => new HeroBanner()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'badge_text' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:3072',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        }

        HeroBanner::create($validated);

        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero Banner berhasil ditambahkan.');
    }

    public function edit(HeroBanner $heroBanner): View
    {
        return view('admin.hero_banners.form', ['banner' => $heroBanner]);
    }

    public function update(Request $request, HeroBanner $heroBanner): RedirectResponse
    {
        $validated = $request->validate([
            'badge_text' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:3072',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($heroBanner->image_path) {
                Storage::disk('public')->delete($heroBanner->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $heroBanner->update($validated);

        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero Banner berhasil diperbarui.');
    }

    public function destroy(HeroBanner $heroBanner): RedirectResponse
    {
        if ($heroBanner->image_path) {
            Storage::disk('public')->delete($heroBanner->image_path);
        }

        $heroBanner->delete();

        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero Banner berhasil dihapus.');
    }
}
