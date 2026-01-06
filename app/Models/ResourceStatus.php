<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceStatus extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function vehicleStatusHistories():HasMany{
        return $this->hasMany(VehicleStatusHistory::class, 'status_id');
    }

    public function toolStatusHistories():HasMany{
        return $this->hasMany(ToolStatusHistory::class, 'status_id');
    }


}
