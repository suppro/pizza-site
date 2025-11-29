<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public $timestamps = false;
    protected $table = 'ProductVariant';

    protected $fillable = ['product_id', 'size_name', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}