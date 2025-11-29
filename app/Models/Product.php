<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $table = 'Product';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'Product_Ingredient', 'product_id', 'ingredient_id');
    }
}