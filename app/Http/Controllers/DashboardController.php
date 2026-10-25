<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with statistics and charts.
     */
    public function index(): View
    {
        $user = auth()->user();
        $invitations = $user->invitations()->with('guests')->get();

        // Basic invitation counts
        $stats = [
            'total_invitations' => $invitations->count(),
            'published_invitations' => $invitations->where('is_published', true)->count(),
            'draft_invitations' => $invitations->where('is_published', false)->count(),
        ];

        // Aggregate guests
        $allGuests = $invitations->flatMap->guests;

        $stats['total_guests'] = $allGuests->count();
        $stats['confirmed_guests'] = $allGuests->whereIn('attendance', ['confirmed', 'attending'])->count();
        $stats['declined_guests'] = $allGuests->whereIn('attendance', ['declined', 'not_attending'])->count();
        $stats['pending_guests'] = $allGuests->where('attendance', 'pending')->count();

        // Attendance percentages
        $chartData = [
            'confirmed' => $stats['total_guests'] > 0
                ? (int) round(($stats['confirmed_guests'] / $stats['total_guests']) * 100)
                : 0,
            'declined' => $stats['total_guests'] > 0
                ? (int) round(($stats['declined_guests'] / $stats['total_guests']) * 100)
                : 0,
            'pending' => $stats['total_guests'] > 0
                ? (int) round(($stats['pending_guests'] / $stats['total_guests']) * 100)
                : 0,
        ];

        // Recent RSVPs
        $invitationIds = $invitations->pluck('id');
        $recentRsvps = Guest::whereIn('invitation_id', $invitationIds)
            ->with('invitation:id,groom_name,bride_name')
            ->whereNotNull('responded_at')
            ->latest('responded_at')
            ->take(5)
            ->get();

        // Recent invitations
        $recentInvitations = $user->invitations()
            ->with('template')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard.index', compact('stats', 'chartData', 'recentRsvps', 'recentInvitations'));
    }
}
