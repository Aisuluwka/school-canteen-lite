<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['name', 'type', 'day', 'weight', 'price'];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
