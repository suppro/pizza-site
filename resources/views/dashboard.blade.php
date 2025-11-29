<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Личный кабинет — Вжух! Пицца</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <!-- Шапка -->
    <header class="bg-red-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-3xl font-bold">Вжух! Пицца</a>
            <div class="flex items-center gap-6">
                @if(session('user_id'))
                    <span class="hidden md:block">Привет, {{ session('user_name') }}!</span>
                    <a href="{{ route('cart') }}" class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100 relative">
                        Корзина
                        @if(session('cart') && collect(session('cart'))->count())
                            <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-xs rounded-full w-6 h-6 flex items-center justify-center">
                                {{ collect(session('cart'))->sum('quantity') }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('orders') }}" class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Мои заказы
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-red-600 px-6 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Войти
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Приветствие -->
    <main class="container mx-auto px-4 py-12">
        @if(session('user_id'))
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Добро пожаловать, {{ session('user_name') }}!</h1>
                <p class="text-xl text-gray-600">Что бы вы хотели заказать сегодня?</p>
            </div>

            <!-- Меню -->
            <h2 class="text-4xl font-bold text-center mb-12">Наше меню</h2>

            @foreach(\App\Models\Category::all() as $category)
                <section class="mb-16">
                    <h3 class="text-3xl font-semibold mb-8 text-red-600">{{ $category->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach(\App\Models\Product::where('category_id', $category->id)->where('is_active', 1)->get() as $product)
                            <div class="bg-white rounded-xl shadow-xl overflow-hidden hover:shadow-2xl transition">
                                @if($product->image)
                                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                         class="w-full h-64 object-cover">
                                @else
                                    <div class="bg-gray-200 h-64 flex items-center justify-center">
                                        <span class="text-gray-500">Фото скоро</span>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h4 class="text-2xl font-bold mb-2">{{ $product->name }}</h4>
                                    <p class="text-gray-600 mb-4">{{ $product->description }}</p>

                                    <form action="{{ route('cart.add') }}" method="POST" class="flex items-center gap-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <select name="variant_id" class="border rounded-lg px-3 py-2" required>
                                            @foreach($product->variants as $variant)
                                                <option value="{{ $variant->id }}">
                                                    {{ $variant->size_name }} — {{ $variant->price }} ₽
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                                class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-semibold">
                                            В корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @else
            <div class="text-center">
                <h2 class="text-3xl font-bold mb-4">Вы не авторизованы</h2>
                <a href="{{ route('login') }}" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                    Войти в аккаунт
                </a>
            </div>
        @endif
    </main>
</body>
</html>