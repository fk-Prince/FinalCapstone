<?php

namespace App\Interfaces;

interface ISubscriptionPayment
{

    public function subscriptionInvoice(array $payload, array $subscription);
}
