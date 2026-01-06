<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
    ];

    public function supplies():HasMany
    {
        return $this->hasMany(Supply::class);
    }

    public function vehicles():HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

}
