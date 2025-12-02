<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Детали заказа #{{ $order->id }} — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-8">
        <!-- Хлебные крошки -->
        <div class="mb-6">
            <a href="{{ route('admin.orders') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Назад к списку заказов
            </a>
        </div>

        <!-- Заголовок -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">Заказ #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-2 font-medium">Создан: {{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 rounded-xl text-lg font-bold shadow-md
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
                <div class="text-3xl font-extrabold mt-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                    {{ number_format($order->total_amount, 0, ',', ' ') }} ₽
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Информация о клиенте -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Информация о клиенте</h2>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Имя:</span>
                        <span class="ml-2">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Телефон:</span>
                        <span class="ml-2">{{ $order->customer_phone ?? '—' }}</span>
                    </div>
                    @if($order->user && $order->user->email)
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <span class="ml-2">{{ $order->user->email }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="font-medium text-gray-700">Адрес доставки:</span>
                        <p class="ml-2 mt-1">{{ $order->delivery_address }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Способ оплаты:</span>
                        <span class="ml-2">
                            {{ \App\Helpers\OrderHelper::getPaymentMethodLabel($order->payment_method) }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Способ доставки:</span>
                        <span class="ml-2">
                            {{ \App\Helpers\OrderHelper::getDeliveryMethodLabel($order->delivery_method) }}
                        </span>
                    </div>
                    @if(isset($order->customer_details['comment']) && $order->customer_details['comment'])
                    <div>
                        <span class="font-medium text-gray-700">Комментарий:</span>
                        <p class="ml-2 mt-1 bg-gray-50 p-3 rounded-lg">{{ $order->customer_details['comment'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Состав заказа -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Состав заказа</h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b-2 border-gray-100 pb-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $item->product_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">Артикул: <span class="font-mono font-semibold">{{ $item->product_sku }}</span></p>
                            <p class="text-sm text-gray-500 mt-1">Количество: <span class="font-semibold">{{ $item->quantity }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="font-extrabold text-lg text-gray-900">{{ number_format($item->price_at_purchase * $item->quantity, 0, ',', ' ') }} ₽</p>
                            <p class="text-sm text-gray-500">{{ number_format($item->price_at_purchase, 0, ',', ' ') }} ₽ × {{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="border-t-2 border-gray-200 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-700">Итого:</span>
                            <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                                {{ number_format($order->total_amount, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Смена статуса -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mt-8 border border-gray-100">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Смена статуса заказа</h2>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-4">
                @csrf
                <select name="status" class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                    <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>Новый</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Выполнен</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                </select>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    Обновить статус
                </button>
            </form>
        </div>
    </div>
</body>
</html>