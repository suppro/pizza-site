<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редактировать товар — Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-4 inline-block">
                ← Назад к списку товаров
            </a>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Редактировать товар</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Название товара *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Артикул (SKU) *</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium font-mono">
                        @error('sku')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Категория</label>
                        <select name="category_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="">Без категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Цена *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        @error('price')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Остаток на складе *</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        @error('stock_quantity')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Статус</label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-gray-700 font-medium">Активен</span>
                        </label>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Описание</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>

                    @if($product->images->count() > 0)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Текущие изображения</label>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach($product->images as $image)
                                    <div class="relative">
                                        <img src="{{ $image->url }}" alt="{{ $product->name }}" 
                                             class="w-full h-32 object-cover rounded-lg border-2 {{ $image->is_main ? 'border-blue-500' : 'border-gray-200' }}">
                                        <div class="absolute top-2 right-2 flex gap-1">
                                            @if($image->is_main)
                                                <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">Главное</span>
                                            @else
                                                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="main_image_id" value="{{ $image->id }}">
                                                    <button type="submit" class="bg-gray-600 text-white px-2 py-1 rounded text-xs font-bold hover:bg-gray-700">
                                                        Сделать главным
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.products.images.delete', ['id' => $product->id, 'imageId' => $image->id]) }}" method="POST" 
                                                  onsubmit="return confirm('Удалить это изображение?');" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold hover:bg-red-600">
                                                    ×
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Добавить изображения</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Можно загрузить несколько изображений.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 font-bold transition-colors">
                        Отмена
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

