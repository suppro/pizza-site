<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мои заказы — Вжух! Пицца</title>
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
                    <a href="{{ route('cart') }}" class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Корзина
                    </a>
                    <a href="{{ route('orders') }}" class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Мои заказы
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100">Выйти</button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold mb-8">Мои заказы</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-bold">Заказ #{{ $order->id }}</h2>
                            <p class="text-gray-600">Дата: {{ $order->created_at }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status_id == 1) bg-yellow-100 text-yellow-800
                                @elseif($order->status_id == 5) bg-green-100 text-green-800
                                @elseif($order->status_id == 6) bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $order->status->name }}
                            </span>
                            <p class="text-lg font-bold mt-2">{{ $order->total_price }} ₽</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                        @if($order->comment)
                            <p class="mt-2"><strong>Комментарий:</strong> {{ $order->comment }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-xl text-gray-600">У вас пока нет заказов</p>
                <a href="{{ route('dashboard') }}" class="mt-4 inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                    Сделать первый заказ
                </a>
            </div>
        @endif
    </div>
</body>
</html>