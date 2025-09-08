<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['student_name', 'class_id'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function menuItems()
{
    return $this->belongsToMany(MenuItem::class, 'order_menu_item');
}

}
