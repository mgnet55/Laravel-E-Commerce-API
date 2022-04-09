<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable=
           [
               'name',
               'description',
               'discount',
               'user_id',
               'image',
               'price',
               'quantity',
               'fulfilled',
               'order_id'
           ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
