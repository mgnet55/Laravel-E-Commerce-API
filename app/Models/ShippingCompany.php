<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'phone',
        'city_id',
        'address_street'
    ];

    protected $hidden = [
        'shipping_manager_id'
    ];

    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShippingManager::class,'shipping_manager_id','id');
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class)->with(Governorate::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function onWayOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class)
            ->where('status', '=', 'on Way')
            ->orderBy('id', 'desc');
    }

    public function deliveredOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class)
            ->where('status', '=', 'Done')
            ->orderBy('id', 'desc');
    }

    public function processingOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class)
            ->where('status', '=', 'Processing');

    }

    //products waiting to be picked up
    public function waitingPickup()
    {
        return $this->hasManyThrough(OrderItems::class, Order::class)
            ->where('picked', '=', false)
            ->with('seller:id,name,phone,address,');
    }

    //products picked up waiting to be delivered to customer
    public function pickedItems(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(OrderItems::class, Order::class)
            ->where('picked', '=', true)
            ->with('seller:id,name,phone,address,')
            ->orderBy('id', 'desc');
    }


}
