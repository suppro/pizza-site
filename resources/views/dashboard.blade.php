<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Личный кабинет — Вжух! Пицца</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">

    <!-- Шапка -->
<header class="bg-gradient-to-r from-red-600 to-red-700 text-white shadow-xl sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
    <div class="container mx-auto px-4 py-5 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-3xl font-extrabold tracking-tight hover:scale-105 transition-transform duration-200">
            🍕 Вжух! Пицца
        </a>
        <div class="flex items-center gap-4">
            @if(session('user_id'))
                <span class="hidden md:block text-yellow-100 font-semibold">Привет, {{ session('user_name') }}! 👋</span>
                
                <!-- ЕСЛИ АДМИН - ПОКАЗЫВАЕМ ССЫЛКУ В АДМИНКУ -->
                @if(session('user_role') == 1)
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-xl font-bold hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        Админ-панель
                    </a>
                @endif
                
                <a href="{{ route('cart') }}" class="bg-white text-red-600 px-5 py-2.5 rounded-xl font-bold hover:bg-yellow-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 relative">
                    🛒 Корзина
                    @if(session('cart') && collect(session('cart'))->count())
                        <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                            {{ collect(session('cart'))->sum('quantity') }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders') }}" class="bg-white text-red-600 px-5 py-2.5 rounded-xl font-bold hover:bg-yellow-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    📦 Заказы
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-white text-red-600 px-5 py-2.5 rounded-xl font-bold hover:bg-yellow-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-white text-red-600 px-6 py-2.5 rounded-xl font-bold hover:bg-yellow-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    Войти
                </a>
            @endif
        </div>
    </div>
</header>

    <!-- Приветствие -->
    <main class="container mx-auto px-4 py-12">
        @if(session('user_id'))
            <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-3xl shadow-2xl p-8 mb-12 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="relative z-10">
                    <h1 class="text-5xl font-extrabold mb-4">Добро пожаловать, {{ session('user_name') }}! 👋</h1>
                    <p class="text-xl text-red-50 font-medium">Что бы вы хотели заказать сегодня?</p>
                </div>
            </div>

            <!-- Меню -->
            <div class="text-center mb-12">
                <h2 class="text-5xl font-extrabold text-gray-900 mb-4">Наше меню</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-red-600 to-orange-500 mx-auto rounded-full"></div>
            </div>

            @foreach(\App\Models\Category::orderByRaw("FIELD(name, 'Пицца', 'Закуски', 'Десерты', 'Напитки')")->get() as $category)
                <section class="mb-20">
                    <h3 class="text-4xl font-bold mb-10 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600">
                        {{ $category->name }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach(\App\Models\Product::where('category_id', $category->id)->where('is_active', 1)->get() as $product)
                            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                                <div class="relative overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 h-64 flex items-center justify-center">
                                            <span class="text-gray-400 text-lg">Фото скоро</span>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                        <span class="text-red-600 font-bold">От {{ $product->variants->min('price') }} ₽</span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h4 class="text-2xl font-bold mb-3 text-gray-900 group-hover:text-red-600 transition-colors">{{ $product->name }}</h4>
                                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->description }}</p>

                                    @if($product->variants->count() > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            
                                            @if($product->variants->count() > 1)
                                                <!-- Если больше одного варианта - показываем выпадающий список -->
                                                <select name="variant_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 font-medium transition-all" required>
                                                    @foreach($product->variants as $variant)
                                                        <option value="{{ $variant->id }}">
                                                            {{ $variant->size_name }} — {{ $variant->price }} ₽
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <!-- Если только один вариант - скрытое поле -->
                                                <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                                            @endif
                                            
                                            <button type="submit"
                                                    class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white px-6 py-3.5 rounded-xl hover:from-red-700 hover:to-orange-700 font-bold whitespace-nowrap shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                                🛒 В корзину
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-sm text-gray-500 text-center py-4">Товар временно недоступен</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @else
            <div class="text-center py-20">
                <div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl p-12">
                    <h2 class="text-3xl font-bold mb-4 text-gray-900">Вы не авторизованы</h2>
                    <p class="text-gray-600 mb-8">Войдите в аккаунт, чтобы начать заказывать</p>
                    <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-red-600 to-orange-600 text-white px-8 py-4 rounded-xl hover:from-red-700 hover:to-orange-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        Войти в аккаунт →
                    </a>
                </div>
            </div>
        @endif
    </main>
</body>
</html>