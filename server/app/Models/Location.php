<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $primaryKey = 'location_id';

    public $timestamps = false;

    protected $fillable = [
        'street',
        'postal_code',
        'city',
        'province',
        'country',
        'longitude',
        'latitude',
    ];

    public function userLocations()
    {
        return $this->hasMany(User::class, 'location_id', 'location_id');
    }

    public function agencyLocations()
    {
        return $this->hasMany(Agency::class, 'location_id', 'location_id');
    }
    public function branchLocations()
    {
        return $this->hasMany(Branch::class, 'location_id', 'location_id');
    }
}
