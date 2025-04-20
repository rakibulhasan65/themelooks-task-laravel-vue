<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
     use HasFactory;

    protected $fillable = [
        'product_id',
        'attributes',
        'purchase_price',
        'selling_price',
        'stock'
    ];

    protected $casts = [
        'attributes' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}