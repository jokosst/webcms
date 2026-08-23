<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(): View
    {
        $profile = CompanyProfile::firstOrCreate(
            ['id' => 1],
            ['company_name' => 'Nama Perusahaan Anda']
        );

        return view('admin.company_profile.edit', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = CompanyProfile::findOrFail(1);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'announcement_ticker' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'about_us' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
            'stat_population' => 'nullable|string|max:100',
            'stat_area' => 'nullable|string|max:100',
            'stat_subdistricts' => 'nullable|string|max:100',
            'stat_umkm' => 'nullable|string|max:100',
            'leader_name' => 'nullable|string|max:255',
            'leader_position' => 'nullable|string|max:255',
            'leader_term' => 'nullable|string|max:255',
            'leader_photo' => 'nullable|image|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'google_maps_embed' => 'nullable|string',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_x' => 'nullable|url|max:255',
            'related_links' => 'nullable|array',
            'related_links.*.title' => 'nullable|string|max:255',
            'related_links.*.url' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            if ($profile->logo_path) {
                Storage::disk('public')->delete($profile->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('company', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($profile->favicon_path) {
                Storage::disk('public')->delete($profile->favicon_path);
            }
            $validated['favicon_path'] = $request->file('favicon')->store('company', 'public');
        }

        if ($request->hasFile('leader_photo')) {
            if ($profile->leader_photo_path) {
                Storage::disk('public')->delete($profile->leader_photo_path);
            }
            $validated['leader_photo_path'] = $request->file('leader_photo')->store('company', 'public');
        }

        $validated['social_media'] = [
            'facebook' => $request->input('social_facebook'),
            'instagram' => $request->input('social_instagram'),
            'linkedin' => $request->input('social_linkedin'),
            'youtube' => $request->input('social_youtube'),
            'x' => $request->input('social_x'),
        ];

        // Process related links array filtering empty titles/urls
        $rawRelatedLinks = $request->input('related_links', []);
        $formattedRelatedLinks = [];
        if (is_array($rawRelatedLinks)) {
            foreach ($rawRelatedLinks as $link) {
                if (!empty($link['title']) && !empty($link['url'])) {
                    $formattedRelatedLinks[] = [
                        'title' => trim($link['title']),
                        'url' => trim($link['url']),
                    ];
                }
            }
        }
        $validated['related_links'] = $formattedRelatedLinks;

        $profile->update($validated);

        return back()->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
