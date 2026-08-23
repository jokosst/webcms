<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InboxLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InboxController extends Controller
{
    public function index(): View
    {
        $leads = InboxLead::latest()->paginate(15);

        return view('admin.inbox.index', compact('leads'));
    }

    public function show(InboxLead $inboxLead): View
    {
        if ($inboxLead->status === 'unread') {
            $inboxLead->update(['status' => 'read']);
        }

        return view('admin.inbox.show', ['lead' => $inboxLead]);
    }

    public function updateStatus(Request $request, InboxLead $inboxLead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied',
        ]);

        $inboxLead->update($validated);

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(InboxLead $inboxLead): RedirectResponse
    {
        $inboxLead->delete();

        return redirect()->route('admin.inbox.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
