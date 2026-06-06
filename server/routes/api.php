<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('/me', fn(Request $request) => $request->user()->load('roles'));
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/subscription/success', [SubscriptionController::class, 'subscriptionWebhook']); // SUBSCRIPTION GCASH WEBHOOK
});

Route::apiResources([
    'plans' => PlanController::class
]);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/subscription', [SubscriptionController::class, 'newSubscription']);
    Route::get('/subscription-detail', [SubscriptionController::class, 'retrieveSubscriptionDetail']);
    Route::post('/subscription-validate', [SubscriptionController::class, 'validateSubscription']);



    Route::apiResources([
        'agencies' => AgencyController::class
    ]);
});
