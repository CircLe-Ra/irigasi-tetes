<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['id','name','description'];

    public function channels() {
        return $this->hasMany(RelayChannel::class);
    }
}
