<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Seller extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

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
        'email',
        'avatar',

    ];

    protected $with=[
        'city'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean'

    ];

    protected static function booted()
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('active', '=', true);
        });
    }
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class)
            ->orderBy('id', 'desc');
    }

    public function availableProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class)
            ->where('available','=',true)
            ->orderBy('id', 'desc');
    }

    public function noStockProducts(){
        return $this->hasMany(Product::class)
            ->where('quantity','=',0)
            ->orderBy('updated_at', 'desc');
    }

    public function unavailableProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class)
            ->where('available','=',false)
            ->orderBy('id', 'desc');
    }

    public function pendingOrders(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(orderItems::class, Product::class)
            ->where('picked', '=', false)
            ->orderBy('id');
    }

    public function pickedOrders(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(orderItems::class, Product::class)
            ->where('picked', '=', true)
            ->orderBy('id', 'desc');
    }

    public function fulfilled(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(orderItems::class, Product::class)
            ->where('fulfilled', '=', true)
            ->where('picked', '=', true)
            ->orderBy('id', 'desc');
    }

    public function unfulfilled(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(orderItems::class, Product::class)
            ->where('fulfilled', '=', false)
            ->where('picked', '=', true)
            ->orderBy('id');
    }

    public function allOrders()
    {
        return $this->hasManyThrough(orderItems::class, Product::class);
    }


}
