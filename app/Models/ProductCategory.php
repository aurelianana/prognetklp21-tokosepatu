<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_name'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_details', 'category_id', 'product_id');
    }
}