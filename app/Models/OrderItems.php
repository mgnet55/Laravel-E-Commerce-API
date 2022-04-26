<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

//    protected $fillable=
//           [
//               'name',
//               'description',
//               'discount',
//               'user_id',
//               'image',
//               'price',
//               'quantity',
//               'fulfilled',
//               'order_id'
//           ];

    protected $hidden=[
        'updated_at',
        'customer_id',
        'updated_at',
        ];

    protected $casts=[
        'created_at'=>'datetime'
    ];

    protected $appends = [
        'sale_price',
        'total',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getSalePriceAttribute(): float|int
    {
        return $this->price*(1-$this->discount);
    }

    public function getTotalAttribute(){
        return ($this->quantity*$this->price*(1-$this->discount));
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
