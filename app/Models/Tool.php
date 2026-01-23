<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type_id',
        'images',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ToolType::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ToolStatusHistory::class);
    }

    public function latestStatusHistory(): HasOne
    {
        return $this->hasOne(ToolStatusHistory::class)
            ->latestOfMany();
    }

    public function getCurrentStatusAttribute()
    {
        return $this->latestStatusHistory?->status;
    }
}
