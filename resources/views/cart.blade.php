<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корзина — Вжух! Пицца</title>
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

    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">🛒 Корзина</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-red-600 to-orange-500 mx-auto rounded-full"></div>
        </div>

        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-2xl mb-6 shadow-lg flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 border border-gray-100">
                <!-- Заголовок таблицы -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b-2 border-gray-200">
                    <div class="grid grid-cols-12 gap-4 text-sm font-bold text-gray-700">
                        <div class="col-span-5">Товар</div>
                        <div class="col-span-2 text-center">Размер</div>
                        <div class="col-span-2 text-center">Цена</div>
                        <div class="col-span-2 text-center">Количество</div>
                        <div class="col-span-1 text-center">Действия</div>
                    </div>
                </div>

                <!-- Список товаров -->
                <div class="divide-y divide-gray-100">
                    @foreach(session('cart') as $id => $item)
                        <div class="px-6 py-6 hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 transition-all duration-200">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Название товара -->
                                <div class="col-span-5">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $item['variant']->product->name }}</h3>
                                    @if($item['variant']->product->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($item['variant']->product->description, 50) }}</p>
                                    @endif
                                </div>

                                <!-- Размер -->
                                <div class="col-span-2 text-center">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg font-semibold text-sm">{{ $item['variant']->size_name }}</span>
                                </div>

                                <!-- Цена -->
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-bold text-gray-900">{{ $item['variant']->price }} ₽</span>
                                </div>

                                <!-- Количество -->
                                <div class="col-span-2 text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="button" onclick="this.form.quantity.value = parseInt(this.form.quantity.value) - 1; if(this.form.quantity.value >= 1) this.form.submit()" 
                                                class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 font-bold text-lg">
                                            −
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                               class="w-16 text-center border-2 border-gray-300 rounded-xl py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 font-semibold"
                                               onchange="if(this.value >= 1) this.form.submit()">
                                        <button type="button" onclick="this.form.quantity.value = parseInt(this.form.quantity.value) + 1; this.form.submit()"
                                                class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 font-bold text-lg">
                                            +
                                        </button>
                                    </form>
                                </div>

                                <!-- Удаление и сумма -->
                                <div class="col-span-1 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-10 h-10 flex items-center justify-center text-red-600 hover:bg-red-100 rounded-full transition-all duration-200 hover:scale-110 font-bold"
                                                    title="Удалить из корзины">
                                                ✕
                                            </button>
                                        </form>
                                        <div class="text-base font-bold text-gray-900">
                                            {{ $item['variant']->price * $item['quantity'] }} ₽
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Итого и кнопка оформления -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-8 border-t-2 border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="text-xl text-gray-700 font-semibold">
                            Товаров: <span class="text-red-600 font-bold">{{ collect(session('cart'))->sum('quantity') }}</span> шт.
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600 mb-6">
                                Итого: {{ collect(session('cart'))->sum(fn($i) => $i['variant']->price * $i['quantity']) }} ₽
                            </div>
                            <a href="{{ route('checkout') }}" 
                               class="bg-gradient-to-r from-red-600 to-orange-600 text-white px-10 py-4 rounded-2xl text-xl font-bold hover:from-red-700 hover:to-orange-700 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-red-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 inline-flex items-center gap-3">
                                Оформить заказ 
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Дополнительные действия -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6">
                <a href="{{ route('dashboard') }}" 
                   class="text-red-600 hover:text-red-700 font-semibold inline-flex items-center gap-2 transition-all duration-200 hover:gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Вернуться к меню
                </a>
                
                <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-gray-600 hover:text-red-600 font-semibold inline-flex items-center gap-2 transition-all duration-200 hover:gap-3"
                            onclick="return confirm('Вы уверены, что хотите очистить корзину?')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Очистить корзину
                    </button>
                </form>
            </div>
        @else
            <!-- Пустая корзина -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl p-12">
                    <div class="text-8xl mb-6">🛒</div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Корзина пуста</h2>
                    <p class="text-gray-600 mb-8 text-lg">Добавьте товары из меню, чтобы сделать заказ</p>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-block bg-gradient-to-r from-red-600 to-orange-600 text-white px-10 py-4 rounded-2xl text-lg font-bold hover:from-red-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Перейти к меню
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>