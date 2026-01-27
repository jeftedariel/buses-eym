<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{

    protected $fillable = [
        'name',
    ];

    public function toolAssignments(): HasMany
    {
        return $this->hasMany(ToolAssignment::class);
    }

    public function activeToolAssignments(): HasMany
    {
        return $this->hasMany(ToolAssignment::class)->whereNull('returned_at');
    }

    public function assignedTools()
    {
        return $this->activeToolAssignments()->with('tool');
    }
}
