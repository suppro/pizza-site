<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корзина — Вжух! Пицца</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <!-- Шапка (та же, что на главной) -->
    <x-header />

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold mb-8">Корзина</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left">Товар</th>
                            <th class="px-6 py-4 text-center">Размер</th>
                            <th class="px-6 py-4 text-center">Цена</th>
                            <th class="px-6 py-4 text-center">Кол-во</th>
                            <th class="px-6 py-4 text-center">Сумма</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('cart') as $id => $item)
                            <tr class="border-t">
                                <td class="px-6 py-4">{{ $item['variant']->product->name }}</td>
                                <td class="px-6 py-4 text-center">{{ $item['variant']->size_name }}</td>
                                <td class="px-6 py-4 text-center">{{ $item['variant']->price }} ₽</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                               class="w-16 text-center border rounded" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-center font-bold">
                                    {{ $item['variant']->price * $item['quantity'] }} ₽
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800">✕</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="bg-gray-50 px-6 py-6 text-right">
                    <div class="text-2xl font-bold">
                        Итого: {{ collect(session('cart'))->sum(fn($i) => $i['variant']->price * $i['quantity']) }} ₽
                    </div>
                    <a href="{{ route('checkout') }}" class="mt-4 inline-block bg-red-600 text-white px-8 py-4 rounded-lg text-xl font-bold hover:bg-red-700">
                        Оформить заказ →
                    </a>
                </div>
            </div>
        @else
            <p class="text-xl text-gray-600 text-center">Корзина пуста. <a href="{{ route('home') }}" class="text-red-600 hover:underline">Вернуться в меню</a></p>
        @endif
    </div>
</body>
</html>