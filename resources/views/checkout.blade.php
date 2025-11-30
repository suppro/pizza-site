<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Оформление заказа — Вжух! Пицца</title>
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
                    
                    <!-- ЕСЛИ АДМИН - ПОКАЗЫВАЕМ ССЫЛКУ В АДМИНКУ -->
                    @if(session('user_role') == 1)
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">
                            Админ-панель
                        </a>
                    @endif
                    
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
        <h1 class="text-4xl font-bold mb-8">Оформление заказа</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Форма заказа -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Данные для доставки</h2>
                
                <form action="{{ route('order.create') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700">Адрес доставки *</label>
                            <input type="text" id="delivery_address" name="delivery_address" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ session('user_address') }}"
                                   placeholder="Укажите адрес доставки">
                            @error('delivery_address')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">Комментарий к заказу</label>
                            <textarea id="comment" name="comment" rows="4"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                      placeholder="Дополнительные пожелания..."></textarea>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full mt-6 bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 font-medium text-lg">
                        Подтвердить заказ
                    </button>
                </form>
            </div>

            <!-- Информация о заказе -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Ваш заказ</h2>
                
                <div class="space-y-4">
                    @foreach($cart as $item)
                    <div class="flex justify-between items-center border-b pb-4">
                        <div>
                            <h3 class="font-semibold">{{ $item['variant']->product->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $item['variant']->size_name }}</p>
                            <p class="text-sm text-gray-600">Количество: {{ $item['quantity'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">{{ $item['variant']->price * $item['quantity'] }} ₽</p>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Итого:</span>
                            <span>{{ $total }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>