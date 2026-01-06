<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'status_id',
        'description',
    ];

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ResourceStatus::class);
    }
}
