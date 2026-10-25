<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicInvitationController extends Controller
{
    /**
     * Display the public wedding invitation page.
     */
    public function show(string $slug): View
    {
        $invitation = Invitation::where('slug', $slug)
            ->with(['template', 'galleries' => function ($query): void {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        // If invitation is draft, only allow owner to preview
        if (! $invitation->is_published && (! auth()->check() || auth()->id() !== $invitation->user_id)) {
            abort(404);
        }

        $wishes = Guest::where('invitation_id', $invitation->id)
            ->whereNotNull('message')
            ->where('message', '!=', '')
            ->latest('responded_at')
            ->take(30)
            ->get();

        $guestStats = [
            'total' => $invitation->guests()->count(),
            'confirmed' => $invitation->guests()->confirmed()->count(),
            'declined' => $invitation->guests()->declined()->count(),
        ];

        return view('public.invitation', [
            'invitation' => $invitation,
            'wishes' => $wishes,
            'guestStats' => $guestStats,
        ]);
    }

    /**
     * Handle public RSVP submission from guests.
     */
    public function rsvp(Request $request, string $slug): JsonResponse
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        // If draft, only owner can test RSVP
        if (! $invitation->is_published && (! auth()->check() || auth()->id() !== $invitation->user_id)) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'attendance' => 'required|in:confirmed,declined,pending,attending,not_attending',
            'message' => 'nullable|string|max:1000',
        ]);

        $guest = Guest::create([
            'invitation_id' => $invitation->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'attendance' => $validated['attendance'],
            'message' => $validated['message'] ?? null,
            'responded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih, konfirmasi kehadiran dan doa restu Anda telah kami terima!',
            'guest' => [
                'name' => $guest->name,
                'attendance' => $guest->attendance->value ?? $guest->attendance,
                'message' => $guest->message,
                'created_at' => 'Baru saja',
            ],
        ]);
    }
}
