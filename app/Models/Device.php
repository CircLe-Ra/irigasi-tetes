<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['id','name','description'];
    protected $with = ['channels'];

    public function channels() {
        return $this->hasMany(RelayChannel::class);
    }

    public function getIsActiveAttribute()
    {
        return $this->channels->contains('state', 1);
    }

    public function soils() {
        return $this->hasMany(Soil::class);
    }
}
