<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'slug',
        'price',
        'description',
        'product_rate',
        'stock',
        'weight',
        'kondisi',
    ];

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_details', 'product_id', 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }


    public function couriers()
    {
        return $this->belongsToMany(Courier::class, 'couriers', 'id', 'courier');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function response()
    {
        return $this->hasOne(Response::class, 'review_id');
    }

    public function transaction_detail()
    {
        return $this->hasOne(TransactionDetail::class);
    }

    public function getImageAttribute()
    {
        if ($this->images->count() > 0) {
            return $this->images->first()->image_name;
        }
    }
}