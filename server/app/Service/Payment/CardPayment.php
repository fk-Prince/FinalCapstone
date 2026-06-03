<?php

namespace App\Service\Payment;

use App\Interfaces\ISubscriptionPayment;
use App\Service\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CardPayment implements ISubscriptionPayment
{
    private string $secretKey;
    private string $encoded;
    private SubscriptionService $subscriptionService;
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->secretKey = env('XENDIT_SECRET_KEY');
        $this->encoded = base64_encode($this->secretKey . ':');
        $this->subscriptionService = $subscriptionService;
    }

    public function subscriptionInvoice(array $payload, array $subscription)
    {
        $user = Auth::user();
        $reference = (string) Str::uuid();;
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->encoded,
            'Content-Type'  => 'application/json',
        ])->post('https://api.xendit.co/credit_card_charges', [
            'token_id'    => $payload['token_id'],
            'authentication_id' => $payload['authentication_id'],
            'capture'     => true,
            'descriptor'  => 'subscription',
            'currency'    => 'PHP',
            'external_id' =>  $reference,
            'amount' => $subscription['total_amount'],
            'payer_email' => $user->email,
            'payment_methods' => ['CREDIT-CARD'],
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

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Charge failed.',
                'error'   => $response->json(),
            ], $response->status());
        }

        $charge = $response->json();

        $cardPayment = [
            'metadata' => $charge['metadata'] ?? [],
            'external_id' => $charge['external_id'] ?? null,
            'xendit_invoice_id' => $charge['id'] ?? null,
        ];

        return $this->subscriptionService->newSubscriber($cardPayment);
    }
}


// try {
//     return $this->subscriptionService->newSubscriber($cardPayment);
// } catch (\Throwable $e) {

//     $this->refundCharge(
//         $charge['id'],
//         $charge['amount']
//     );

//     throw $e;
// }

// private function refundCharge(string $chargeId, float $amount): void
// {
//     Http::withBasicAuth($this->secretKey, '')
//         ->post("https://api.xendit.co/refunds", [
//             'reference_id' => Str::uuid(),
//             'type' => 'FULL_REFUND',
//             'amount' => $amount,
//             'payment_request_id' => $chargeId,
//             'reason' => 'Database transaction failed',
//         ]);
// }