<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'capacity',
        'transmission',
        'motor_displacement',
        'color',
        'license_plate',
        'images',
        'description',
        'available',
        'displayable',
        'model_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'available' => 'boolean',
        'displayable' => 'boolean',
        'images' => 'array',
    ];

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(VehicleStatusHistory::class);
    }
}
