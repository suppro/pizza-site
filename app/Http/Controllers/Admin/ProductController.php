<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $query = Product::with(['category', 'mainImage']);

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Фильтр по категории
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Фильтр по активности
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'technical_specs' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        
        // Обработка технических характеристик
        if (isset($validated['technical_specs'])) {
            $validated['technical_specs'] = json_encode($validated['technical_specs']);
        }

        $product = Product::create($validated);

        // Загрузка изображений
        if ($request->hasFile('images')) {
            $isMain = true;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $isMain && $index == 0,
                    'sort_order' => $index,
                ]);
                
                $isMain = false;
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан!');
    }

    public function show($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $product = Product::with(['category', 'images'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'technical_specs' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        
        // Обработка технических характеристик
        if (isset($validated['technical_specs'])) {
            $validated['technical_specs'] = json_encode($validated['technical_specs']);
        }

        $product->update($validated);

        // Загрузка новых изображений
        if ($request->hasFile('images')) {
            $existingImagesCount = $product->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $existingImagesCount == 0 && $index == 0,
                    'sort_order' => $existingImagesCount + $index,
                ]);
            }
        }

        // Обновление главного изображения
        if ($request->filled('main_image_id')) {
            ProductImage::where('product_id', $product->id)->update(['is_main' => false]);
            ProductImage::where('id', $request->main_image_id)
                ->where('product_id', $product->id)
                ->update(['is_main' => true]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлен!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($id);
        
        // Удаляем изображения
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно удален!');
    }

    public function deleteImage($id, $imageId)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $image = ProductImage::where('id', $imageId)
            ->where('product_id', $id)
            ->firstOrFail();
        
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        
        $image->delete();

        return back()->with('success', 'Изображение удалено!');
    }
}
