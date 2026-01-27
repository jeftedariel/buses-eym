<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolAssignment extends Model
{
    protected $fillable = [
        'tool_id',
        'employee_id',
        'assigned_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function isActive(): bool
    {
        return is_null($this->returned_at);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('returned_at');
    }

    public function scopeReturned($query)
    {
        return $query->whereNotNull('returned_at');
    }
}
