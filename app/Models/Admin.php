<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Model
{
    protected $table = 'users';
    use HasFactory,HasRoles;

    protected $fillable=['name','email','password','phone','city_id','address'];
}
