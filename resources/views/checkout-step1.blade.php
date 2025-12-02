<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Оформление заказа — Шаг 1 — АО «Арвиай»</title>
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
                    <a href="{{ route('cart') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        🛒 Корзина
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <!-- Индикатор шагов -->
        <div class="max-w-3xl mx-auto mb-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">1</div>
                    <span class="font-semibold text-blue-600">Данные клиента</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-4"></div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold">2</div>
                    <span class="font-semibold text-gray-500">Доставка и оплата</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-4"></div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold">3</div>
                    <span class="font-semibold text-gray-500">Подтверждение</span>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Оформление заказа</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Форма -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Шаг 1: Контактные данные</h2>
                    
                    <form action="{{ route('checkout.step2') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-bold text-gray-700 mb-2">Имя *</label>
                                <input type="text" id="customer_name" name="customer_name" required
                                       class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                       value="{{ auth()->user()->name ?? old('customer_name') }}"
                                       placeholder="Ваше имя">
                                @error('customer_name')
                                    <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-bold text-gray-700 mb-2">Телефон *</label>
                                <input type="tel" id="customer_phone" name="customer_phone" required
                                       class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                       value="{{ auth()->user()->phone ?? old('customer_phone') }}"
                                       placeholder="+79991234567">
                                @error('customer_phone')
                                    <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" id="customer_email" name="customer_email"
                                       class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                       value="{{ auth()->user()->email ?? old('customer_email') }}"
                                       placeholder="email@example.com">
                                @error('customer_email')
                                    <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-8">
                            <a href="{{ route('cart') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                ← Вернуться к корзине
                            </a>
                            <button type="submit"
                                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-8 rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                Далее →
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Информация о заказе -->
                <div class="bg-white rounded-2xl shadow-2xl p-6 border border-gray-100">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-4">Ваш заказ</h3>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cart as $item)
                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-gray-900">{{ $item['product']->name }}</p>
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

