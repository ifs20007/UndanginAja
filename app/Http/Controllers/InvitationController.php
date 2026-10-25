<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InvitationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of user's invitations.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()->invitations()->with('template');

        // Search by groom or bride name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('groom_name', 'like', "%{$search}%")
                    ->orWhere('bride_name', 'like', "%{$search}%");
            });
        }

        // Filter by publication status
        if ($request->filled('status')) {
            if ($request->input('status') === 'published') {
                $query->published();
            } elseif ($request->input('status') === 'draft') {
                $query->draft();
            }
        }

        $invitations = $query->latest()->paginate(9)->withQueryString();

        return view('invitations.index', [
            'invitations' => $invitations,
            'currentSearch' => $request->input('search', ''),
            'currentStatus' => $request->input('status', ''),
        ]);
    }

    /**
     * Show the form for creating a new invitation.
     */
    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user->canCreateInvitation()) {
            return redirect()->route('invitations.index')
                ->with('error', 'Kamu telah mencapai batas maksimal kuota undangan untuk paket ini. Silakan upgrade paket langganan Anda.');
        }

        $templates = Template::all();

        return view('invitations.create', [
            'freeTemplates' => $templates->where('is_premium', false),
            'premiumTemplates' => $templates->where('is_premium', true),
            'canUsePremium' => $user->hasActiveSubscription(),
        ]);
    }

    /**
     * Store a newly created invitation in storage.
     */
    public function store(StoreInvitationRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->canCreateInvitation()) {
            return redirect()->route('invitations.index')
                ->with('error', 'Kamu telah mencapai batas maksimal kuota undangan.');
        }

        $validated = $request->validated();

        // Check premium template access
        if (! empty($validated['template_id'])) {
            $template = Template::find($validated['template_id']);
            if ($template && $template->is_premium && ! $user->hasActiveSubscription()) {
                return back()->with('error', 'Template premium hanya dapat digunakan oleh member Pro / Business. Silakan upgrade terlebih dahulu.');
            }
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['groom_name'].'-dan-'.$validated['bride_name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Invitation::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $validated['user_id'] = $user->id;
        $validated['slug'] = $slug;

        // Process theme_config
        if ($request->has('theme_config')) {
            $rawConfig = $request->input('theme_config', []);
            if (! empty($rawConfig['bank_name']) || ! empty($rawConfig['bank_account'])) {
                $rawConfig['bank_accounts'] = [
                    [
                        'bank_name' => $rawConfig['bank_name'] ?? 'Bank Central Asia (BCA)',
                        'account_number' => $rawConfig['bank_account'] ?? '',
                        'account_holder' => $rawConfig['bank_owner'] ?? $validated['groom_name'],
                    ],
                ];
            }
            $validated['theme_config'] = $rawConfig;
        }

        // Process love_stories
        if ($request->has('love_stories')) {
            $validated['love_stories'] = collect($request->input('love_stories', []))
                ->filter(fn ($item) => ! empty($item['title']) || ! empty($item['story']))
                ->values()
                ->all();
        }

        // Sync music & dresscode if provided in theme_config
        if (! empty($validated['theme_config']['audio_url']) && empty($validated['music_url'])) {
            $validated['music_url'] = $validated['theme_config']['audio_url'];
        }
        if (! empty($validated['theme_config']['dress_code_desc']) && empty($validated['dresscode'])) {
            $validated['dresscode'] = $validated['theme_config']['dress_code_desc'];
        }

        $invitation = Invitation::create($validated);

        // Process gallery photos if uploaded during creation
        if ($request->hasFile('gallery_photos')) {
            $maxPhotos = $user->getMaxGalleryPhotos();
            $photos = $request->file('gallery_photos');
            foreach (array_slice($photos, 0, $maxPhotos) as $idx => $photo) {
                $path = $photo->store("invitations/{$invitation->id}/galleries", 'public');
                $invitation->galleries()->create([
                    'image_path' => $path,
                    'order' => $idx + 1,
                ]);
            }
        }

        return redirect()->route('invitations.index')
            ->with('success', 'Undangan digital berhasil dibuat! Anda sekarang dapat mengatur detail dan mempublikasikannya.');
    }

    /**
     * Display the specified invitation preview.
     */
    public function show(Invitation $invitation): View
    {
        $this->authorize('view', $invitation);

        $invitation->load('template', 'guests');

        return view('invitations.show', compact('invitation'));
    }

    /**
     * Show the form for editing the specified invitation.
     */
    public function edit(Invitation $invitation): View
    {
        $this->authorize('update', $invitation);

        $invitation->load('galleries');
        $templates = Template::all();

        return view('invitations.edit', [
            'invitation' => $invitation,
            'freeTemplates' => $templates->where('is_premium', false),
            'premiumTemplates' => $templates->where('is_premium', true),
            'canUsePremium' => auth()->user()->hasActiveSubscription(),
        ]);
    }

    /**
     * Update the specified invitation in storage.
     */
    public function update(UpdateInvitationRequest $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('update', $invitation);

        $user = auth()->user();
        $validated = $request->validated();

        if (! empty($validated['template_id'])) {
            $template = Template::find($validated['template_id']);
            if ($template && $template->is_premium && ! $user->hasActiveSubscription()) {
                return back()->with('error', 'Template premium hanya dapat digunakan oleh member Pro / Business.');
            }
        }

        // Process theme_config
        if ($request->has('theme_config')) {
            $rawConfig = $request->input('theme_config', []);
            $existingConfig = $invitation->theme_config ?? [];

            if (! empty($rawConfig['bank_name']) || ! empty($rawConfig['bank_account'])) {
                $rawConfig['bank_accounts'] = [
                    [
                        'bank_name' => $rawConfig['bank_name'] ?? 'Bank Central Asia (BCA)',
                        'account_number' => $rawConfig['bank_account'] ?? '',
                        'account_holder' => $rawConfig['bank_owner'] ?? ($validated['groom_name'] ?? $invitation->groom_name),
                    ],
                ];
            }

            $validated['theme_config'] = array_merge($existingConfig, $rawConfig);
        }

        // Process love_stories
        if ($request->has('love_stories')) {
            $validated['love_stories'] = collect($request->input('love_stories', []))
                ->filter(fn ($item) => ! empty($item['title']) || ! empty($item['story']))
                ->values()
                ->all();
        }

        // Sync music & dresscode if provided in theme_config
        if (! empty($validated['theme_config']['audio_url']) && empty($validated['music_url'])) {
            $validated['music_url'] = $validated['theme_config']['audio_url'];
        }
        if (! empty($validated['theme_config']['dress_code_desc']) && empty($validated['dresscode'])) {
            $validated['dresscode'] = $validated['theme_config']['dress_code_desc'];
        }

        $invitation->update($validated);

        // Process gallery photos if uploaded during update
        if ($request->hasFile('gallery_photos')) {
            $maxPhotos = $user->getMaxGalleryPhotos();
            $currentCount = $invitation->galleries()->count();
            $remaining = max(0, $maxPhotos - $currentCount);

            if ($remaining > 0) {
                foreach (array_slice($request->file('gallery_photos'), 0, $remaining) as $idx => $photo) {
                    $path = $photo->store("invitations/{$invitation->id}/galleries", 'public');
                    $invitation->galleries()->create([
                        'image_path' => $path,
                        'order' => $currentCount + $idx + 1,
                    ]);
                }
            }
        }

        return redirect()->route('invitations.index')
            ->with('success', 'Perubahan pada undangan berhasil disimpan!');
    }

    /**
     * Remove the specified invitation from storage.
     */
    public function destroy(Invitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        $invitation->delete();

        return redirect()->route('invitations.index')
            ->with('success', 'Undangan berhasil dihapus.');
    }

    /**
     * Toggle publication status of the invitation.
     */
    public function togglePublish(Invitation $invitation): RedirectResponse
    {
        $this->authorize('update', $invitation);

        $invitation->update([
            'is_published' => ! $invitation->is_published,
        ]);

        $message = $invitation->is_published
            ? 'Undangan berhasil dipublikasikan! Link publik Anda: '.$invitation->public_url
            : 'Undangan berhasil diubah statusnya menjadi draft (unpublish).';

        return back()->with('success', $message);
    }
}
