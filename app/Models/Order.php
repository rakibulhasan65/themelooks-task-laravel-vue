<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'order_number',
        'subtotal',
        'tax',
        'discount',
        'total',
        'items',
        'customer_name',
        'customer_phone'
    ];

    protected $casts = [
        'items' => 'array'
    ];
}