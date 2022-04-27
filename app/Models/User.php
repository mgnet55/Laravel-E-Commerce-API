<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'address',
        'city_id',
        'phone',
        'active',
        'bank_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at',
        'updated_at',
        'bank_id',
        'city_id',

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
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id');
    }

    public function admin()
    {

        return $this->belongsTo(Admin::class, 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'id');
    }

    public function ShippingManager()
    {
        return $this->belongsTo(ShippingManager::class,'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function governorate()
    {
        return $this->belongsToThrough(Governorate::class, City::class);
    }


//    public function getLocationAttribute()
//    {
//        return
//         [
//             'city_id'=>$this->city->id,
//             'city_name'=>$this->city()->first()->name,
//             'governorate_id'=>$this->city()->first()->governerate_id,
//             'governorate_name'=>$this->city()->first()->governorate_name,
//             'address'=>$this->address
//         ];
//
//    }

//    public function getGovernorateNameAttribute()
//    {
//        return $this->city()->first()->governorate_name;
//    }

//    public function getGovernorateNameAttribute()
//    {
//        return $this->city()->governorate()->first('governorate.name');
//    }

}
