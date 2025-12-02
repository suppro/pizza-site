<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Оформление заказа — Подтверждение — АО «Арвиай»</title>
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
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-4 py-2.5 rounded-xl font-bold hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            Админ-панель
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <!-- Индикатор шагов -->
        <div class="max-w-3xl mx-auto mb-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold">✓</div>
                    <span class="font-semibold text-green-600">Данные клиента</span>
                </div>
                <div class="flex-1 h-1 bg-green-500 mx-4"></div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold">✓</div>
                    <span class="font-semibold text-green-600">Доставка и оплата</span>
                </div>
                <div class="flex-1 h-1 bg-blue-600 mx-4"></div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">3</div>
                    <span class="font-semibold text-blue-600">Подтверждение</span>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Подтверждение заказа</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Форма подтверждения -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Шаг 3: Проверьте данные заказа</h2>
                    
                    <!-- Данные клиента -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-xl border border-blue-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Контактные данные</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Имя:</span> {{ $customerData['name'] }}</p>
                            <p><span class="font-semibold">Телефон:</span> {{ $customerData['phone'] }}</p>
                            @if(!empty($customerData['email']))
                                <p><span class="font-semibold">Email:</span> {{ $customerData['email'] }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Доставка и оплата -->
                    <div class="mb-6 p-6 bg-indigo-50 rounded-xl border border-indigo-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Доставка и оплата</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Адрес доставки:</span> {{ $deliveryData['address'] }}</p>
                            <p><span class="font-semibold">Способ доставки:</span> {{ \App\Helpers\OrderHelper::getDeliveryMethodLabel($deliveryData['method']) }}</p>
                            <p><span class="font-semibold">Способ оплаты:</span> {{ \App\Helpers\OrderHelper::getPaymentMethodLabel($deliveryData['payment']) }}</p>
                            @if(!empty($deliveryData['comment']))
                                <p><span class="font-semibold">Комментарий:</span> {{ $deliveryData['comment'] }}</p>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('order.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_name" value="{{ $customerData['name'] }}">
                        <input type="hidden" name="customer_phone" value="{{ $customerData['phone'] }}">
                        <input type="hidden" name="customer_email" value="{{ $customerData['email'] ?? '' }}">
                        <input type="hidden" name="delivery_address" value="{{ $deliveryData['address'] }}">
                        <input type="hidden" name="delivery_method" value="{{ $deliveryData['method'] }}">
                        <input type="hidden" name="payment_method" value="{{ $deliveryData['payment'] }}">
                        <input type="hidden" name="comment" value="{{ $deliveryData['comment'] ?? '' }}">

                        <div class="flex justify-between items-center mt-8">
                            <a href="{{ route('checkout.step2') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                ← Назад
                            </a>
                            <button type="submit"
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 px-10 rounded-xl hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                ✅ Подтвердить заказ
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Итоговая информация -->
                <div class="bg-white rounded-2xl shadow-2xl p-6 border border-gray-100">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-4">Состав заказа</h3>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cart as $item)
                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-gray-900">{{ $item['product']->name }}</p>
                                @if($item['product']->sku)
                                    <p class="text-xs text-gray-500">Артикул: {{ $item['product']->sku }}</p>
                                @endif
                                <p class="text-xs text-gray-500">× {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-bold text-gray-900">{{ number_format($item['product']->price * $item['quantity'], 0, ',', ' ') }} ₽</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t-2 border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700 font-semibold">Товаров:</span>
                            <span class="text-gray-900 font-bold">{{ collect($cart)->sum('quantity') }} шт.</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-700">Итого:</span>
                            <span class="text-2xl font-extrabold text-blue-600">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

