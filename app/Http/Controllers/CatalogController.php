<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        
        // Базовый запрос
        $productsQuery = Product::where('is_active', true)->with('mainImage');
        
        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $productsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Фильтр по категории
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $category = Category::find($categoryId);
            
            if ($category) {
                // Получаем ID всех подкатегорий
                $categoryIds = [$category->id];
                foreach ($category->children as $child) {
                    $categoryIds[] = $child->id;
                }
                $productsQuery->whereIn('category_id', $categoryIds);
            }
        }
        
        // Фильтр по наличию
        if ($request->filled('stock')) {
            if ($request->stock == 'in_stock') {
                $productsQuery->where('stock_quantity', '>', 0);
            } elseif ($request->stock == 'out_of_stock') {
                $productsQuery->where('stock_quantity', '<=', 0);
            }
        }
        
        // Фильтр по цене
        if ($request->filled('price_min')) {
            $productsQuery->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $productsQuery->where('price', '<=', $request->price_max);
        }
        
        // Сортировка
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        switch ($sort) {
            case 'price':
                $productsQuery->orderBy('price', $direction);
                break;
            case 'name':
            default:
                $productsQuery->orderBy('name', $direction);
                break;
        }
        
        $products = $productsQuery->get();
        
        // Получаем диапазон цен для фильтра
        $priceRange = Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
        
        return view('dashboard', compact('categories', 'products', 'priceRange'));
    }
}
