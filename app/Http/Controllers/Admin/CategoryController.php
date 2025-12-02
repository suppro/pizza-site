<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $categories = Category::with(['parent', 'children', 'products'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно создана!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->get();
        
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|different:' . $id,
            'description' => 'nullable|string',
        ]);

        // Предотвращаем создание циклических зависимостей
        if ($validated['parent_id']) {
            $parent = Category::find($validated['parent_id']);
            if ($parent && $parent->parent_id == $id) {
                return back()->withErrors(['parent_id' => 'Нельзя выбрать дочернюю категорию в качестве родительской']);
            }
        }

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $category = Category::findOrFail($id);
        
        // Проверяем, есть ли товары в категории
        if ($category->products()->count() > 0) {
            return back()->withErrors(['error' => 'Нельзя удалить категорию, в которой есть товары']);
        }
        
        // Проверяем, есть ли подкатегории
        if ($category->children()->count() > 0) {
            return back()->withErrors(['error' => 'Нельзя удалить категорию, у которой есть подкатегории']);
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена!');
    }
}
