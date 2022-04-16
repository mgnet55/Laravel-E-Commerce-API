<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description','quantity','price','image','user_id','category_id','available'
    ];

    protected $casts=[
      'available'=>'boolean'

    ];

    protected $hidden=[
        'created_at', 'updated_at','category_id'
    ];


    function seller(){
        return $this->belongsTo(User::class);
    }

    function category(){
        return $this->belongsTo(Category::class);
    }



}
