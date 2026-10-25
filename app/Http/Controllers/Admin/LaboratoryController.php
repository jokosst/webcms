<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LaboratoryController extends Controller
{
    public function index(): View
    {
        $laboratories = Laboratory::withCount(['bookings', 'approvedBookings'])
            ->orderBy('order_index', 'asc')
            ->get();

        return view('admin.laboratories.index', compact('laboratories'));
    }

    public function create(): View
    {
        return view('admin.laboratories.form', [
            'laboratory' => new Laboratory(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'pc_specs' => 'nullable|string|max:1000',
            'facilities_raw' => 'nullable|string',
            'description' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_contact' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:3072',
            'is_active' => 'boolean',
            'order_index' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $count = 1;
        while (Laboratory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $count++;
        }

        $validated['is_active'] = $request->has('is_active');

        // Parse facilities from newline-delimited text
        if (!empty($validated['facilities_raw'])) {
            $validated['facilities'] = array_values(array_filter(array_map('trim', explode("\n", $validated['facilities_raw']))));
        } else {
            $validated['facilities'] = [];
        }
        unset($validated['facilities_raw']);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('laboratories', 'public');
        }

        Laboratory::create($validated);

        return redirect()->route('admin.laboratories.index')->with('success', 'Data Laboratorium berhasil ditambahkan.');
    }

    public function edit(Laboratory $laboratory): View
    {
        return view('admin.laboratories.form', compact('laboratory'));
    }

    public function update(Request $request, Laboratory $laboratory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'pc_specs' => 'nullable|string|max:1000',
            'facilities_raw' => 'nullable|string',
            'description' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_contact' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:3072',
            'is_active' => 'boolean',
            'order_index' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Unique slug check excluding current
        $baseSlug = $validated['slug'];
        $count = 1;
        while (Laboratory::where('slug', $validated['slug'])->where('id', '!=', $laboratory->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $count++;
        }

        $validated['is_active'] = $request->has('is_active');

        if (!empty($validated['facilities_raw'])) {
            $validated['facilities'] = array_values(array_filter(array_map('trim', explode("\n", $validated['facilities_raw']))));
        } else {
            $validated['facilities'] = [];
        }
        unset($validated['facilities_raw']);

        if ($request->hasFile('image')) {
            if ($laboratory->image_path) {
                Storage::disk('public')->delete($laboratory->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('laboratories', 'public');
        }

        $laboratory->update($validated);

        return redirect()->route('admin.laboratories.index')->with('success', 'Data Laboratorium berhasil diperbarui.');
    }

    public function destroy(Laboratory $laboratory): RedirectResponse
    {
        if ($laboratory->image_path) {
            Storage::disk('public')->delete($laboratory->image_path);
        }

        $laboratory->delete();

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratorium berhasil dihapus.');
    }
}
