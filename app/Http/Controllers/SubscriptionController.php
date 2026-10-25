<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display pricing plans and subscription status.
     */
    public function index(): View
    {
        $plans = Plan::orderBy('price')->get();
        $user = auth()->user();
        $currentPlan = $user->current_plan;

        return view('subscription.index', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'hasActiveSubscription' => $user->hasActiveSubscription(),
        ]);
    }

    /**
     * Display checkout summary page for selected plan.
     */
    public function checkout(Plan $plan): View|RedirectResponse
    {
        if ($plan->price === 0) {
            return redirect()->route('subscription.index')
                ->with('error', 'Paket gratis tidak memerlukan proses pembayaran checkout.');
        }

        $user = auth()->user();

        return view('subscription.checkout', [
            'plan' => $plan,
            'user' => $user,
            'clientKey' => config('midtrans.client_key'),
        ]);
    }
}
