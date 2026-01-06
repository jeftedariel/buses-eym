<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type_id',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ToolType::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ToolStatusHistory::class);
    }
}
