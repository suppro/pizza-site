<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Каталог — АО «Арвиай»</title>
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
                <span class="hidden md:block text-blue-100 font-semibold">Привет, {{ auth()->user()->name }}! 👋</span>
                
                <!-- ЕСЛИ АДМИН - ПОКАЗЫВАЕМ ССЫЛКУ В АДМИНКУ -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-xl font-bold hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        Админ-панель
                    </a>
                @endif
                
                <a href="{{ route('cart') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 relative">
                    🛒 Корзина
                    @if(session('cart') && collect(session('cart'))->count())
                        <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                            {{ collect(session('cart'))->sum('quantity') }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    📦 Заказы
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    Войти
                </a>
            @endauth
        </div>
    </div>
</header>

    <!-- Приветствие -->
    <main class="container mx-auto px-4 py-12">
        @auth
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl shadow-2xl p-8 mb-12 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="relative z-10">
                    <h1 class="text-5xl font-extrabold mb-4">Добро пожаловать, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-xl text-blue-50 font-medium">Выберите товары из каталога авиационных запчастей</p>
                </div>
            </div>

            <!-- Поиск и фильтры -->
            <div class="mb-8">
                <form action="{{ route('dashboard') }}" method="GET" class="space-y-6">
                    <!-- Поиск -->
                    <div class="max-w-2xl mx-auto">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Поиск по названию, артикулу или описанию..."
                               class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-lg">
                    </div>
                    
                    <!-- Панель фильтров -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Фильтр по категории -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Категория</label>
                                <select name="category" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="">Все категории</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @foreach($cat->children as $child)
                                            <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                                                — {{ $child->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Фильтр по наличию -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Наличие</label>
                                <select name="stock" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="">Все товары</option>
                                    <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>В наличии</option>
                                    <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Нет в наличии</option>
                                </select>
                            </div>
                            
                            <!-- Фильтр по цене -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Цена от</label>
                                <input type="number" name="price_min" value="{{ request('price_min') }}" 
                                       placeholder="0" min="0"
                                       class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Цена до</label>
                                <input type="number" name="price_max" value="{{ request('price_max') }}" 
                                       placeholder="{{ $priceRange->max_price ?? '999999' }}" min="0"
                                       class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            </div>
                        </div>
                        
                        <!-- Сортировка и кнопки -->
                        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-bold text-gray-700">Сортировка:</label>
                                <select name="sort" class="px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>По названию</option>
                                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>По цене</option>
                                </select>
                                <select name="direction" class="px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                                </select>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    Применить фильтры
                                </button>
                                <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-300 font-bold transition-colors">
                                    Сбросить
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Заголовок -->
            <div class="text-center mb-12">
                <h2 class="text-5xl font-extrabold text-gray-900 mb-4">Каталог товаров</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 mx-auto rounded-full"></div>
            </div>

            <!-- Результаты -->
            @if(request()->hasAny(['search', 'category', 'stock', 'price_min', 'price_max']))
                <!-- Результаты поиска/фильтрации -->
                @if($products->count() > 0)
                    <div class="mb-6">
                        <p class="text-gray-600">Найдено товаров: <strong>{{ $products->count() }}</strong></p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-12 text-center">
                        <p class="text-gray-500 text-xl mb-4">Товары не найдены</p>
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Сбросить фильтры</a>
                    </div>
                @endif
            @else
                <!-- Категории -->
                @foreach($categories as $category)
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
                                    @include('partials.product-card', ['product' => $product])
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-8 text-center">
                                <p class="text-gray-500 text-lg">В этой категории пока нет товаров</p>
                            </div>
                        @endif
                    </section>
                @endforeach
            @endif
        @else
            <div class="text-center py-20">
                <div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl p-12">
                    <h2 class="text-3xl font-bold mb-4 text-gray-900">Вы не авторизованы</h2>
                    <p class="text-gray-600 mb-8">Войдите в аккаунт, чтобы начать заказывать</p>
                    <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        Войти в аккаунт →
                    </a>
                </div>
            </div>
        @endauth
    </main>
</body>
</html>
