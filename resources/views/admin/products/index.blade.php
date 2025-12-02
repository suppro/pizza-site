<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Товары — Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    use Illuminate\Support\Str;
@endphp
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Управление товарами</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full"></div>
            </div>
            <a href="{{ route('admin.products.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                + Добавить товар
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Фильтры -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Поиск по названию или артикулу..."
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                </div>
                <div>
                    <select name="category_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        <option value="">Все категории</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="is_active" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        <option value="">Все товары</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Активные</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Неактивные</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 font-bold transition-colors">
                        Применить
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-300 font-bold transition-colors">
                        Сбросить
                    </a>
                </div>
            </form>
        </div>

        <!-- Таблица товаров -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold">ID</th>
                            <th class="px-6 py-4 text-left font-bold">Изображение</th>
                            <th class="px-6 py-4 text-left font-bold">Название</th>
                            <th class="px-6 py-4 text-left font-bold">Артикул</th>
                            <th class="px-6 py-4 text-left font-bold">Категория</th>
                            <th class="px-6 py-4 text-left font-bold">Цена</th>
                            <th class="px-6 py-4 text-left font-bold">Остаток</th>
                            <th class="px-6 py-4 text-left font-bold">Статус</th>
                            <th class="px-6 py-4 text-left font-bold">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold">{{ $product->id }}</td>
                                <td class="px-6 py-4">
                                    @if($product->mainImage)
                                        <img src="{{ $product->mainImage->url }}" alt="{{ $product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">Нет фото</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm">{{ $product->sku }}</td>
                                <td class="px-6 py-4">
                                    @if($product->category)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                                <td class="px-6 py-4">
                                    @if($product->stock_quantity > 0)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-semibold">
                                            0
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($product->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">Активен</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold">Неактивен</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 font-semibold text-sm transition-colors">
                                            Редактировать
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" 
                                              onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 font-semibold text-sm transition-colors">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    Товары не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>

