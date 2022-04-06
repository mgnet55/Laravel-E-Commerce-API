<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=
           [
               'city_id',
               'street',
               'status',
               'user_id',
               'shipping_company_id',
               'payment_ref'
           ];


    public function shipping_companies()
    {
//        return $this->belongsTo(Shipping_Company::class)
    }
    public function customer()
    {
//        return $this->belongsTo(User::class,'user_id');
    }
}
