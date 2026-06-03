<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Service\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function newSubscription(SubscriptionRequest $request)
    {
        return $this->subscriptionService->makeSubscription($request->all());
    }

    public function validateSubscription(SubscriptionRequest $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Validation passed',
            'data' => $request->validated(),
        ]);
    }

    public function subscriptionWebhook(Request $request)
    {
        return $this->subscriptionService->subscriptionWebhook($request);
    }


    public function retrieveSubscriptionDetail(Request $request)
    {
        return $this->subscriptionService->createSubscription($request->all());
    }
}
