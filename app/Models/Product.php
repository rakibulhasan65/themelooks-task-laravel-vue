<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
     protected $fillable = [
        'name', 
        'sku', 
        'unit', 
        'unit_value', 
        'selling_price', 
        'purchase_price',
        'discount', 
        'tax', 
        'image'
    ];

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function getFinalPriceAttribute()
    {
        $price = $this->selling_price;
        
        // Apply discount if any
        if ($this->discount > 0) {
            $price = $price - ($price * ($this->discount / 100));
        }
        
        // Add tax if any
        if ($this->tax > 0) {
            $price = $price + ($price * ($this->tax / 100));
        }
        
        return round($price, 2);
    }
}