<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\InvitationGalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TransactionController;
use App\Models\Plan;
use App\Models\Template;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $plans = Plan::all();
        $templates = Template::take(6)->get();
    } catch (Throwable) {
        $plans = collect();
        $templates = collect();
    }

    return view('welcome', compact('plans', 'templates'));
});

// Public Invitation, RSVP & Template Preview routes (No auth)
Route::get('/undangan/{slug}', [PublicInvitationController::class, 'show'])
    ->name('public.invitation');
Route::post('/undangan/{slug}/rsvp', [PublicInvitationController::class, 'rsvp'])
    ->name('public.rsvp');
Route::get('/templates/{template:slug}/preview', [TemplateController::class, 'preview'])
    ->name('templates.preview');

// Midtrans Payment Webhook (No auth, CSRF exempted in bootstrap/app.php)
Route::post('/payments/notification', [TransactionController::class, 'notification'])
    ->name('payments.notification');

// Authenticated Application routes
Route::middleware(['auth', 'verified'])->group(function (): void {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Invitations CRUD & Actions
    Route::resource('invitations', InvitationController::class);
    Route::post('/invitations/{invitation}/toggle-publish', [InvitationController::class, 'togglePublish'])
        ->name('invitations.togglePublish');

    // Gallery Photo Management
    Route::post('/invitations/{invitation}/galleries', [InvitationGalleryController::class, 'store'])
        ->name('invitations.galleries.store');
    Route::delete('/invitations/{invitation}/galleries/{gallery}', [InvitationGalleryController::class, 'destroy'])
        ->name('invitations.galleries.destroy');

    // Guest Management per Invitation
    Route::get('/invitations/{invitation}/guests', [GuestController::class, 'index'])
        ->name('guests.index');
    Route::get('/invitations/{invitation}/guests/export', [GuestController::class, 'export'])
        ->name('guests.export');
    Route::delete('/guests/{guest}', [GuestController::class, 'destroy'])
        ->name('guests.destroy');

    // Templates Gallery
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');
    Route::post('/templates/apply', [TemplateController::class, 'apply'])->name('templates.apply');

    // Subscription & Transactions
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription/checkout/{plan:slug}', [SubscriptionController::class, 'checkout'])
        ->name('subscription.checkout');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
