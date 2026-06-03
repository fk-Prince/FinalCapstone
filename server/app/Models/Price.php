<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'price',
        'valid_from',
        'valid_to',
    ];
}
