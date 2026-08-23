<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(): View
    {
        $team = TeamMember::orderBy('sort_order', 'asc')->get();

        return view('admin.team_members.index', compact('team'));
    }

    public function create(): View
    {
        return view('admin.team_members.form', ['member' => new TeamMember()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'bio' => 'nullable|string',
            'linkedin' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        $validated['social_links'] = [
            'linkedin' => $request->input('linkedin'),
            'twitter' => $request->input('twitter'),
            'instagram' => $request->input('instagram'),
        ];

        unset($validated['photo'], $validated['linkedin'], $validated['twitter'], $validated['instagram']);

        TeamMember::create($validated);

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $teamMember): View
    {
        return view('admin.team_members.form', ['member' => $teamMember]);
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'bio' => 'nullable|string',
            'linkedin' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($teamMember->photo_path && Storage::disk('public')->exists($teamMember->photo_path)) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        $validated['social_links'] = [
            'linkedin' => $request->input('linkedin'),
            'twitter' => $request->input('twitter'),
            'instagram' => $request->input('instagram'),
        ];

        unset($validated['photo'], $validated['linkedin'], $validated['twitter'], $validated['instagram']);

        $teamMember->update($validated);

        return redirect()->route('admin.team-members.index')->with('success', 'Profil tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }

        $teamMember->delete();

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
