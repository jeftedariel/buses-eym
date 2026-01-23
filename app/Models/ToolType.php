<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToolType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manufacturer_id',
        'category_id',
        'images',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ToolCategory::class);
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class, 'type_id');
    }
}
