<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates with category filter.
     */
    public function index(Request $request): View
    {
        $query = Template::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $templates = $query->orderBy('is_premium')->orderBy('name')->get();

        $categories = [
            'elegant' => 'Elegant',
            'rustic' => 'Rustic',
            'modern' => 'Modern',
            'minimalist' => 'Minimalist',
        ];

        $userInvitations = auth()->user()->invitations()->select('id', 'groom_name', 'bride_name')->get();

        return view('templates.index', [
            'templates' => $templates,
            'categories' => $categories,
            'currentCategory' => $request->input('category'),
            'userInvitations' => $userInvitations,
            'canUsePremium' => auth()->user()->hasActiveSubscription(),
        ]);
    }

    /**
     * Display the template detail page with preview information.
     */
    public function show(Template $template): View
    {
        $dummyData = [
            'groom_name' => 'Wahyu Pratama',
            'bride_name' => 'Riski Rahmawati',
            'event_date' => now()->addMonths(2),
            'event_time' => '09:00',
            'venue_name' => 'Gedung Serbaguna Balai Kartini',
            'venue_address' => 'Jl. Gatot Subroto No. 123, Jakarta Selatan',
            'message' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kedua mempelai.',
        ];

        return view('templates.show', [
            'template' => $template,
            'dummyData' => $dummyData,
            'canUsePremium' => auth()->user()->hasActiveSubscription(),
        ]);
    }

    /**
     * Display the full interactive template preview page.
     */
    public function preview(Template $template): View
    {
        $dummyInvitation = new Invitation([
            'slug' => 'preview-'.$template->slug,
            'groom_name' => 'Wahyu Pratama',
            'bride_name' => 'Riski Rahmawati',
            'event_date' => now()->addMonths(2),
            'event_time' => '09:00',
            'venue_name' => 'Gedung Serbaguna Balai Kartini',
            'venue_address' => 'Jl. Gatot Subroto No. 123, Jakarta Selatan',
            'google_maps_url' => 'https://maps.google.com/?q=Balai+Kartini',
            'message' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kedua mempelai.',
            'is_published' => true,
        ]);
        $dummyInvitation->setRelation('template', $template);
        $dummyInvitation->setRelation('galleries', collect());

        $wishes = collect([
            (object) [
                'name' => 'Budi Santoso',
                'attendance' => 'confirmed',
                'message' => 'Selamat menempuh hidup baru Wahyu & Riski! Semoga menjadi keluarga yang sakinah mawaddah warahmah.',
                'responded_at' => now()->subHours(2),
                'created_at' => now()->subHours(2),
            ],
            (object) [
                'name' => 'Siti Aisyah',
                'attendance' => 'confirmed',
                'message' => 'Barakallahu lakuma wa baraka alaikuma wa jama\'a bainakuma fii khair.',
                'responded_at' => now()->subDays(1),
                'created_at' => now()->subDays(1),
            ],
        ]);

        $guestStats = [
            'total' => 25,
            'confirmed' => 22,
            'declined' => 3,
        ];

        return view('public.invitation', [
            'invitation' => $dummyInvitation,
            'wishes' => $wishes,
            'guestStats' => $guestStats,
            'isPreview' => true,
        ]);
    }

    /**
     * Apply selected template to an invitation.
     */
    public function apply(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'template_id' => 'required|exists:templates,id',
        ]);

        $invitation = Invitation::findOrFail($validated['invitation_id']);

        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $template = Template::findOrFail($validated['template_id']);

        if ($template->is_premium && ! auth()->user()->hasActiveSubscription()) {
            return back()->with('error', 'Template premium hanya untuk member Pro / Business. Silakan upgrade terlebih dahulu.');
        }

        $invitation->update(['template_id' => $template->id]);

        return redirect()->route('invitations.edit', $invitation)
            ->with('success', "Template '{$template->name}' berhasil diterapkan pada undangan Anda!");
    }
}
