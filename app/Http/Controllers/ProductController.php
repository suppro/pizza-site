<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'images', 'mainImage'])
            ->firstOrFail();

        return view('product', compact('product'));
    }
}
