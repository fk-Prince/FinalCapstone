<?php

namespace App\Service\Payment;

use App\Interfaces\ISubscriptionPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GCashPayment implements ISubscriptionPayment
{
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = env('XENDIT_SECRET_KEY');
    }

    public function subscriptionInvoice(array $payload, array $subscription)
    {
        $user = Auth::user();
        $reference = (string) Str::uuid();
        $response = Http::withBasicAuth($this->secretKey, '')
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' =>  $reference,
                'amount' => $subscription['total_amount'],
                'payer_email' => $user->email,
                'payment_methods' => ['GCASH'],
                'metadata' => [
                    'type' => $subscription['type'],
                    'plan' => $subscription['plan'],
                    'user' => $subscription['user'],
                    'branch' => $subscription['branch'],
                    'agency' => $subscription['agency'],
                    'billing_interval' => $subscription['billing_interval'],
                    'total_amount' => $subscription['total_amount'],
                    'endDate' => $subscription['endDate'],
                ]
            ]);

        return response()->json($response->json());
    }
}
