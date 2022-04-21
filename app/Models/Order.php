<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'city_id',
            'street',
            'status',
            'customer_id',
            'shipping_company_id',
            'payment_ref',
            'notes'
        ];
    protected $hidden = [];

    protected $appends = [
        'total'
    ];


    public function shipping_companies()
    {
        // return $this->belongsTo(Shipping_Company::class);
    }

    public function customer()
    {
//        return $this->belongsTo(User::class,'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->orderItems as $item) {
            $total += $item->total;
        }
        return $total;
    }

}
