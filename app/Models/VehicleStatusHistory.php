<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'status_id',
        'description',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ResourceStatus::class);
    }
}
