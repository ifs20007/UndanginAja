<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationGallery;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvitationGalleryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store new gallery photos for an invitation.
     */
    public function store(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('update', $invitation);

        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'captions' => 'nullable|array',
        ], [
            'photos.required' => 'Pilih minimal satu foto untuk diunggah.',
            'photos.*.image' => 'File harus berupa gambar.',
            'photos.*.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, webp, gif.',
            'photos.*.max' => 'Ukuran maksimal tiap gambar adalah 5MB.',
        ]);

        $maxPhotos = auth()->user()->getMaxGalleryPhotos();
        $currentCount = $invitation->galleries()->count();
        $incomingCount = count($request->file('photos', []));

        if ($currentCount + $incomingCount > $maxPhotos) {
            $limitText = $maxPhotos >= 50 ? 'Unlimited (50)' : (string) $maxPhotos;
            $message = "Gagal mengunggah foto. Batas maksimal galeri untuk paket Anda adalah {$limitText} foto (Saat ini: {$currentCount} foto). Silakan upgrade paket untuk mengunggah lebih banyak.";

            return back()
                ->withErrors(['photos' => $message])
                ->with('error', $message);
        }

        $uploaded = 0;
        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store("invitations/{$invitation->id}/galleries", 'public');
            $caption = $request->input("captions.{$index}");

            $invitation->galleries()->create([
                'image_path' => $path,
                'caption' => $caption,
                'order' => $currentCount + $uploaded + 1,
            ]);
            $uploaded++;
        }

        return back()->with('success', "{$uploaded} foto berhasil ditambahkan ke galeri undangan!");
    }

    /**
     * Remove the specified gallery photo.
     */
    public function destroy(Invitation $invitation, InvitationGallery $gallery): RedirectResponse
    {
        $this->authorize('update', $invitation);

        if ($gallery->invitation_id !== $invitation->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
