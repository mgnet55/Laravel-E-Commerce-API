<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Eloquent
 */
class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'description', 'quantity', 'price', 'image', 'seller_id', 'category_id', 'available'
    ];

    protected $casts = [
        'available' => 'boolean'

    ];

    protected $appends = [
        'soldCount',
        'salePrice'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    function getSoldCountAttribute()
    {
        return OrderItems::where('product_id', '=', $this->id)->sum('quantity');
    }

    public function getSalePriceAttribute(): float|int
    {
        return $this->price * (1 - $this->discount);
    }

    public function ordered()
    {
        return $this->hasMany(OrderItems::class);
    }


}
