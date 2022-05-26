<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'courier_id',
        'timeout',
        'address',
        'province',
        'regency',
        'total',
        'shipping_cost',
        'sub_total',
        'proof_of_payment',
        'code',
        'slug',
        'payment_token',
        'payment_url',
        'city_id',
        'shipping_package',
        'status',
    ];

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_details')->withPivot(['qty', 'selling_price', 'discount']);
    }
}