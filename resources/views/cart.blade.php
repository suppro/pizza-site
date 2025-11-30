<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корзина — Вжух! Пицца</title>
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

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold text-center mb-12">Корзина</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <!-- Заголовок таблицы -->
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                        <div class="col-span-5">Товар</div>
                        <div class="col-span-2 text-center">Размер</div>
                        <div class="col-span-2 text-center">Цена</div>
                        <div class="col-span-2 text-center">Количество</div>
                        <div class="col-span-1 text-center">Действия</div>
                    </div>
                </div>

                <!-- Список товаров -->
                <div class="divide-y">
                    @foreach(session('cart') as $id => $item)
                        <div class="px-6 py-6 hover:bg-gray-50 transition">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Название товара -->
                                <div class="col-span-5">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $item['variant']->product->name }}</h3>
                                    @if($item['variant']->product->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($item['variant']->product->description, 50) }}</p>
                                    @endif
                                </div>

                                <!-- Размер -->
                                <div class="col-span-2 text-center">
                                    <span class="text-gray-700">{{ $item['variant']->size_name }}</span>
                                </div>

                                <!-- Цена -->
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold text-gray-900">{{ $item['variant']->price }} ₽</span>
                                </div>

                                <!-- Количество -->
                                <div class="col-span-2 text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="button" onclick="this.form.quantity.value = parseInt(this.form.quantity.value) - 1; if(this.form.quantity.value >= 1) this.form.submit()" 
                                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                            −
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                               class="w-16 text-center border border-gray-300 rounded-lg py-1 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                               onchange="if(this.value >= 1) this.form.submit()">
                                        <button type="button" onclick="this.form.quantity.value = parseInt(this.form.quantity.value) + 1; this.form.submit()"
                                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                            +
                                        </button>
                                    </form>
                                </div>

                                <!-- Удаление и сумма -->
                                <div class="col-span-1 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-full transition"
                                                    title="Удалить из корзины">
                                                ✕
                                            </button>
                                        </form>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $item['variant']->price * $item['quantity'] }} ₽
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Итого и кнопка оформления -->
                <div class="bg-gray-50 px-6 py-6 border-t">
                    <div class="flex justify-between items-center">
                        <div class="text-lg text-gray-600">
                            Товаров: {{ collect(session('cart'))->sum('quantity') }} шт.
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900 mb-4">
                                Итого: {{ collect(session('cart'))->sum(fn($i) => $i['variant']->price * $i['quantity']) }} ₽
                            </div>
                            <a href="{{ route('checkout') }}" 
                               class="bg-red-600 text-white px-8 py-4 rounded-lg text-xl font-bold hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 inline-flex items-center gap-2">
                                Оформить заказ 
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Дополнительные действия -->
            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard') }}" 
                   class="text-red-600 hover:text-red-700 font-medium inline-flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Вернуться к меню
                </a>
                
                <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-gray-600 hover:text-red-600 font-medium inline-flex items-center gap-2 transition"
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
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Корзина пуста</h2>
                    <p class="text-gray-600 mb-8">Добавьте товары из меню, чтобы сделать заказ</p>
                    <a href="{{ route('dashboard') }}" 
                       class="bg-red-600 text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-red-700 transition inline-flex items-center gap-2">
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