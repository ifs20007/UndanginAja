<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuestController extends Controller
{
    /**
     * Display a listing of guests for a specific invitation.
     */
    public function index(Request $request, Invitation $invitation): View
    {
        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $query = $invitation->guests();

        // Search guest name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        // Filter attendance status
        if ($request->filled('attendance') && $request->input('attendance') !== 'all') {
            $status = $request->input('attendance');
            if ($status === 'confirmed') {
                $query->whereIn('attendance', ['confirmed', 'attending']);
            } elseif ($status === 'declined') {
                $query->whereIn('attendance', ['declined', 'not_attending']);
            } elseif ($status === 'pending') {
                $query->where('attendance', 'pending');
            }
        }

        $guests = $query->latest('responded_at')->paginate(20)->withQueryString();

        $stats = [
            'total' => $invitation->guests()->count(),
            'confirmed' => $invitation->guests()->confirmed()->count(),
            'declined' => $invitation->guests()->declined()->count(),
            'pending' => $invitation->guests()->pending()->count(),
        ];

        return view('guests.index', [
            'invitation' => $invitation,
            'guests' => $guests,
            'stats' => $stats,
            'currentSearch' => $request->input('search', ''),
            'currentAttendance' => $request->input('attendance', 'all'),
        ]);
    }

    /**
     * Export guest list to a CSV file.
     */
    public function export(Invitation $invitation): StreamedResponse
    {
        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $filename = 'tamu-'.$invitation->slug.'-'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($invitation): void {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Nama', 'No. HP', 'Kehadiran', 'Ucapan', 'Waktu Respon']);

            // Data rows
            $guests = $invitation->guests()->orderByDesc('responded_at')->get();

            foreach ($guests as $guest) {
                $attendanceVal = is_object($guest->attendance) ? $guest->attendance->value : (string) $guest->attendance;
                $attendanceLabel = match ($attendanceVal) {
                    'confirmed', 'attending' => 'Hadir',
                    'declined', 'not_attending' => 'Tidak Hadir',
                    'pending' => 'Masih Ragu',
                    default => '-'
                };

                fputcsv($file, [
                    $guest->name,
                    $guest->phone ?? '-',
                    $attendanceLabel,
                    $guest->message ?? '-',
                    $guest->responded_at ? $guest->responded_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Remove the specified guest from storage.
     */
    public function destroy(Guest $guest): RedirectResponse
    {
        $invitation = $guest->invitation;

        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $guest->delete();

        return redirect()->route('guests.index', $invitation)
            ->with('success', 'Data tamu berhasil dihapus.');
    }
}
