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

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class)->with(Governorate::class);
    }

    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->with(User::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }
}
