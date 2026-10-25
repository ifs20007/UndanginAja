<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Plan;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Snap;

class TransactionController extends Controller
{
    /**
     * Create a new transaction and generate Midtrans Snap Token.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = auth()->user();
        $plan = Plan::findOrFail($request->input('plan_id'));

        if ($plan->price === 0) {
            return response()->json(['error' => 'Paket gratis tidak memerlukan transaksi.'], 400);
        }

        // Generate unique order ID
        $orderId = 'NIKAHYUK-'.$user->id.'-'.time();

        // Create initial transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'order_id' => $orderId,
            'amount' => $plan->price,
            'status' => TransactionStatus::Pending,
        ]);

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $plan->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $plan->slug,
                    'price' => $plan->price,
                    'quantity' => 1,
                    'name' => 'UndanginAja '.$plan->name.' Plan',
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);

            $transaction->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (Exception $e) {
            Log::error('Midtrans Snap Error: '.$e->getMessage());

            return response()->json([
                'error' => 'Gagal membuat token transaksi pembayaran: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle payment notification webhook from Midtrans.
     */
    public function notification(Request $request): JsonResponse
    {
        try {
            $notification = new Notification;

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            $transaction = Transaction::where('order_id', $orderId)->first();

            if (! $transaction) {
                Log::warning('Midtrans Webhook: Transaction not found for Order ID '.$orderId);

                return response()->json(['message' => 'Transaction not found'], 404);
            }

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->handleSuccessPayment($transaction);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->handleSuccessPayment($transaction);
            } elseif ($transactionStatus === 'pending') {
                $transaction->update(['status' => TransactionStatus::Pending]);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $transaction->update(['status' => TransactionStatus::Failed]);
            }

            return response()->json(['message' => 'OK']);
        } catch (Exception $e) {
            Log::error('Midtrans Webhook Notification Error: '.$e->getMessage());

            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Mark transaction as paid and activate user subscription.
     */
    private function handleSuccessPayment(Transaction $transaction): void
    {
        $transaction->update([
            'status' => TransactionStatus::Paid,
            'paid_at' => now(),
        ]);

        $user = $transaction->user;
        $plan = $transaction->plan;

        if ($user && $plan) {
            $user->update([
                'subscription_plan' => $plan->slug,
                'subscription_expires_at' => now()->addDays(30),
            ]);

            Log::info("Subscription activated for User ID: {$user->id}, Plan: {$plan->slug}");
        }
    }
}
