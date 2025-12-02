<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>АО «Арвиай» — Авиационные запчасти</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    use Illuminate\Support\Str;
@endphp
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">

    <!-- Шапка -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-xl sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
        <div class="container mx-auto px-4 py-5 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-3xl font-extrabold tracking-tight hover:scale-105 transition-transform duration-200">
                ✈️ АО «Арвиай»
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('register') }}" class="text-white hover:text-yellow-200 font-semibold transition-colors duration-200">
                    Регистрация
                </a>
                <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    Войти
                </a>
            </div>
        </div>
    </header>

    <!-- Герой-секция -->
    <section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-300 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-6xl md:text-7xl font-extrabold mb-6 leading-tight">
                Авиационные запчасти<br>и комплектующие
            </h2>
            <p class="text-xl md:text-2xl mb-10 text-blue-50 font-medium">Оптово-розничная продажа. Качество и надежность</p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-blue-600 px-10 py-4 rounded-2xl text-xl font-bold hover:bg-blue-50 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                Перейти в каталог →
            </a>
        </div>
    </section>

    <!-- Меню для гостей -->
    <main class="container mx-auto px-4 py-16">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-extrabold text-gray-900 mb-4">Каталог товаров</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 mx-auto rounded-full"></div>
        </div>

        @foreach(\App\Models\Category::whereNull('parent_id')->with('children')->get() as $category)
            <section class="mb-20">
                <h3 class="text-4xl font-bold mb-10 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                    {{ $category->name }}
                </h3>
                @php
                    // Получаем ID всех подкатегорий
                    $categoryIds = [$category->id];
                    foreach ($category->children as $child) {
                        $categoryIds[] = $child->id;
                    }
                    
                    $categoryProducts = \App\Models\Product::whereIn('category_id', $categoryIds)
                        ->where('is_active', true)
                        ->with('mainImage')
                        ->get();
                @endphp
                @if($categoryProducts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($categoryProducts as $product)
                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <div class="relative overflow-hidden">
                                @if($product->mainImage)
                                    <img src="{{ $product->mainImage->url }}" alt="{{ $product->name }}"
                                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 h-64 flex items-center justify-center">
                                        <span class="text-gray-400 text-lg">Фото скоро</span>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                    <span class="text-blue-600 font-bold">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                                </div>
                                @if($product->stock_quantity > 0)
                                    <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                        В наличии: {{ $product->stock_quantity }}
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h4 class="text-2xl font-bold mb-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $product->name }}</h4>
                                @if($product->sku)
                                    <p class="text-sm text-gray-500 mb-3">Артикул: {{ $product->sku }}</p>
                                @endif
                                <p class="text-gray-600 mb-4 leading-relaxed">{{ Str::limit($product->description, 100) }}</p>
                                <div class="text-center pt-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-500 font-medium">Войдите, чтобы заказать</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-8 text-center">
                        <p class="text-gray-500 text-lg">В этой категории пока нет товаров</p>
                    </div>
                @endif
            </section>
        @endforeach

        <!-- Призыв к действию -->
        <div class="text-center mt-20 py-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl shadow-2xl">
            <h3 class="text-4xl font-extrabold text-white mb-6">Хотите сделать заказ?</h3>
            <p class="text-xl text-blue-50 mb-8">Войдите в аккаунт и начните оформлять заказы прямо сейчас!</p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-blue-600 px-10 py-4 rounded-2xl text-xl font-bold hover:bg-blue-50 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                Войдите в аккаунт →
            </a>
        </div>
    </main>
</body>
</html>