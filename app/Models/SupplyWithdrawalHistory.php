<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyWithdrawalHistory extends Model
{
    protected $fillable = [
        'supply_id',
        'quantity',
        'employee_id',
        'notes',
        'withdrawn_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'withdrawn_at' => 'datetime',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
