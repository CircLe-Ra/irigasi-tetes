<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelayChannel extends Model
{
    protected $fillable = ['device_id','channel','state', 'description'];

    public function device() {
        return $this->belongsTo(Device::class);
    }
}
