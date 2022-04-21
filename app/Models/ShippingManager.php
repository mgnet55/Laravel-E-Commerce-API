<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ShippingManager extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;    protected $table = 'users';


    public function shippingCompany(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ShippingCompany::class,'shipping_manager_id');
    }

}

