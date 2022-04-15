<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{

    protected $table = 'users';
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'deleted_at',
        'bank_id',
        'active',
        'email',
        'avatar',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'picked' => 'boolean',

    ];

    protected static function booted()
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('active', '=', true);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id')
            ->orderBy('id', 'desc');
    }

    public function pendingOrders()
    {
        return $this->hasMany(orderItems::class, 'user_id')
            ->where('picked', '=', false)
            ->orderBy('id');
    }

    public function pickedOrders()
    {
        return $this->hasMany(orderItems::class, 'user_id')
            ->where('picked', '=', true)
            ->orderBy('created_at', 'desc');
    }

    public function fulfilled()
    {
        return $this->hasMany(orderItems::class, 'user_id')
            ->where('fulfilled', '=', true)
            ->where('picked', '=', true)
            ->orderBy('id', 'desc');
    }

    public function unfulfilled()
    {
        return $this->hasMany(orderItems::class, 'user_id')
            ->where('fulfilled', '=', false)
            ->where('picked', '=', true)
            ->orderBy('id');
    }

    public function orderItems()
    {
        return $this->hasMany(orderItems::class, 'user_id');
    }


}
