<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} — АО «Арвиай»</title>
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
                @auth
                    <a href="{{ route('cart') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 relative">
                        🛒 Корзина
                        @if(session('cart') && collect(session('cart'))->count())
                            <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                {{ collect(session('cart'))->sum('quantity') }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        Каталог
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        Войти
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <!-- Хлебные крошки -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Каталог</a></li>
                @if($product->category)
                    <li>/</li>
                    <li><a href="{{ route('dashboard', ['category' => $product->category->id]) }}" class="hover:text-blue-600 transition-colors">{{ $product->category->name }}</a></li>
                @endif
                <li>/</li>
                <li class="text-gray-900 font-semibold">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
            <!-- Изображения товара -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                @if($product->mainImage)
                    <div class="aspect-square rounded-xl overflow-hidden mb-4">
                        <img src="{{ $product->mainImage->url }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
                        <span class="text-gray-400 text-xl">Фото скоро</span>
                    </div>
                @endif
                
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images->take(4) as $image)
                            <div class="aspect-square rounded-lg overflow-hidden border-2 {{ $image->is_main ? 'border-blue-600' : 'border-gray-200' }}">
                                <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Информация о товаре -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="mb-6">
                    @if($product->category)
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold mb-3">
                            {{ $product->category->name }}
                        </span>
                    @endif
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    @if($product->sku)
                        <p class="text-lg text-gray-600 mb-4">
                            <span class="font-semibold">Артикул:</span> 
                            <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg">{{ $product->sku }}</span>
                        </p>
                    @endif
                </div>

                <div class="mb-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="flex items-baseline gap-3 mb-2">
                        <span class="text-4xl font-extrabold text-blue-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                    </div>
                    @if($product->stock_quantity > 0)
                        <div class="flex items-center gap-2 text-green-700 font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>В наличии: {{ $product->stock_quantity }} шт.</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-red-600 font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Нет в наличии</span>
                        </div>
                    @endif
                </div>

                @if($product->description)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Описание</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                @if($product->technical_specs && count($product->technical_specs) > 0)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Технические характеристики</h3>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                            @foreach($product->technical_specs as $key => $value)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                    <span class="font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                    <span class="text-gray-900 font-medium">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @auth
                    @if($product->stock_quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="flex items-center gap-4">
                                <label for="quantity" class="text-lg font-semibold text-gray-700">Количество:</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                       class="w-24 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-center">
                            </div>
                            
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                🛒 Добавить в корзину
                            </button>
                        </form>
                    @else
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-center">
                            <p class="text-red-700 font-semibold">Товар временно недоступен</p>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-center">
                        <p class="text-blue-700 font-semibold mb-3">Войдите, чтобы добавить товар в корзину</p>
                        <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition-colors">
                            Войти
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </main>
</body>
</html>

