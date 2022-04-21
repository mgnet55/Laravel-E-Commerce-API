<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description','quantity','price','image','seller_id','category_id','available'
    ];

    protected $casts=[
      'available'=>'boolean'

    ];

    protected $appends=[
        'soldCount',
        'salePrice'
    ];

    protected $hidden=[
        'created_at', 'updated_at'
    ];

    function seller(){
        return $this->belongsTo(Seller::class);
    }

    function category(){
        return $this->belongsTo(Category::class);
    }

    function getSoldCountAttribute(){
        return OrderItems::where('product_id','=',$this->id)->sum('quantity');
    }

    public function getSalePriceAttribute(): float|int
    {
        return $this->price*(1-$this->discount);
    }

}
