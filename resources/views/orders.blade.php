<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мои заказы — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
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
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-2.5 rounded-xl font-bold hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            Админ-панель
                        </a>
                    @endif
                    <a href="{{ route('cart') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        🛒 Корзина
                    </a>
                    <a href="{{ route('orders') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        📦 Заказы
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">Выйти</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">📦 Мои заказы</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 mx-auto rounded-full"></div>
        </div>

        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-2xl mb-6 shadow-lg flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                                    #
                                </div>
                                <h2 class="text-2xl font-extrabold text-gray-900">Заказ #{{ $order->id }}</h2>
                            </div>
                            <p class="text-gray-600 font-medium ml-15">
                                📅 {{ $order->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold shadow-md
                                @if($order->status == 'new') bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900
                                @elseif($order->status == 'completed') bg-gradient-to-r from-green-400 to-green-500 text-green-900
                                @elseif($order->status == 'cancelled') bg-gradient-to-r from-red-400 to-red-500 text-red-900
                                @else bg-gradient-to-r from-blue-400 to-blue-500 text-blue-900 @endif">
                                @if($order->status == 'new') Новый
                                @elseif($order->status == 'processing') В обработке
                                @elseif($order->status == 'completed') Выполнен
                                @elseif($order->status == 'cancelled') Отменен
                                @else {{ $order->status }} @endif
                            </span>
                            <p class="text-3xl font-extrabold mt-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                                {{ number_format($order->total_amount, 0, ',', ' ') }} ₽
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-t-2 border-gray-100 pt-6 space-y-3">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">📍</span>
                            <div>
                                <p class="font-bold text-gray-700 mb-1">Адрес доставки:</p>
                                <p class="text-gray-600">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl">💳</span>
                            <div>
                                <p class="font-bold text-gray-700 mb-1">Способ оплаты:</p>
                                <p class="text-gray-600">
                                    {{ \App\Helpers\OrderHelper::getPaymentMethodLabel($order->payment_method) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl">🚚</span>
                            <div>
                                <p class="font-bold text-gray-700 mb-1">Способ доставки:</p>
                                <p class="text-gray-600">
                                    {{ \App\Helpers\OrderHelper::getDeliveryMethodLabel($order->delivery_method) }}
                                </p>
                            </div>
                        </div>
                        @if(isset($order->customer_details['comment']) && $order->customer_details['comment'])
                            <div class="flex items-start gap-3">
                                <span class="text-xl">💬</span>
                                <div>
                                    <p class="font-bold text-gray-700 mb-1">Комментарий:</p>
                                    <p class="text-gray-600">{{ $order->customer_details['comment'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Состав заказа -->
                    <div class="border-t-2 border-gray-100 pt-6 mt-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Состав заказа:</h3>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ $item->product_name }} (Артикул: {{ $item->product_sku }}) × {{ $item->quantity }}</span>
                                    <span class="font-bold text-gray-900">{{ number_format($item->price_at_purchase * $item->quantity, 0, ',', ' ') }} ₽</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl p-12">
                    <div class="text-8xl mb-6">📦</div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">У вас пока нет заказов</h2>
                    <p class="text-gray-600 mb-8 text-lg">Сделайте свой первый заказ прямо сейчас!</p>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-10 py-4 rounded-2xl text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1">
                        Сделать первый заказ →
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
