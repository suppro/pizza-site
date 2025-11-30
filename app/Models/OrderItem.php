<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'OrderItem';
    
    protected $fillable = [
        'order_id', 'product_variant_id', 'quantity', 'price_at_moment'
    ];
    
    public $timestamps = false;
    
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}