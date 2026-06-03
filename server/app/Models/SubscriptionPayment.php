<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $primaryKey = 'subscription_payment_id';

    protected $fillable = [
        'subscription_id',
        'xendit_invoice_id',
        'payment_reference_id',
        'price_id',
    ];

    public function prices()
    {
        return $this->belongsTo(Price::class, 'price_id', 'price_id');
    }
}
