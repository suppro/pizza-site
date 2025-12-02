@php
    use App\Models\Order;
@endphp

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление заказами — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <!-- Заголовок -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Управление заказами</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-blue-500 mx-auto rounded-full"></div>
        </div>

        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-2xl mb-6 shadow-lg flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Фильтры и поиск -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <form action="{{ route('admin.orders') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Статус заказа</label>
                    <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium transition-all">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium transition-all"
                           placeholder="ID заказа, имя или телефон">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 font-bold w-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        Применить фильтры
                    </button>
                </div>
            </form>
        </div>

        <!-- Список заказов -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Клиент</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Телефон</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Адрес</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Статус</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Сумма</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Дата</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors {{ request('highlight') == $order->id ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-lg">#{{ $order->id }}</div>
                                    <div class="text-sm text-gray-500 font-medium">{{ $order->items->count() }} товаров</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold">{{ $order->customer_name }}</div>
                                    @if($order->user)
                                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold">{{ $order->customer_phone ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs truncate font-medium" title="{{ $order->delivery_address }}">
                                        {{ $order->delivery_address }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <select name="status" 
                                                onchange="this.form.submit()"
                                                class="text-sm border-2 rounded-xl px-3 py-2 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all
                                                    @if($order->status == 'new') bg-yellow-100 text-yellow-800 border-yellow-300
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800 border-green-300
                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800 border-red-300
                                                    @else bg-blue-100 text-blue-800 border-blue-300 @endif">
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 font-extrabold text-lg">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold">{{ date('d.m.Y', strtotime($order->created_at)) }}</div>
                                    <div class="text-xs text-gray-500">{{ date('H:i', strtotime($order->created_at)) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.order.detail', $order->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-bold text-sm hover:underline transition-colors">
                                        Подробнее →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-8xl mb-6">📦</div>
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Заказов не найдено</h3>
                    <p class="text-gray-600 text-lg">Попробуйте изменить параметры фильтрации</p>
                </div>
            @endif
        </div>

        <!-- Статистика по статусам -->
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $statusStats = [
                    'new' => ['name' => 'Новые', 'color' => 'from-yellow-400 to-yellow-500'],
                    'processing' => ['name' => 'В обработке', 'color' => 'from-blue-400 to-blue-500'],
                    'completed' => ['name' => 'Выполненные', 'color' => 'from-green-400 to-green-500'],
                    'cancelled' => ['name' => 'Отмененные', 'color' => 'from-red-400 to-red-500'],
                ];
            @endphp
            
            @foreach($statusStats as $status => $stat)
                @php
                    $count = Order::where('status', $status)->count();
                @endphp
                <div class="bg-white rounded-2xl shadow-xl p-6 text-center border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-gradient-to-br {{ $stat['color'] }} w-16 h-16 rounded-2xl flex items-center justify-center text-white font-extrabold text-2xl mx-auto mb-3 shadow-lg">
                        {{ $count }}
                    </div>
                    <div class="text-sm font-bold text-gray-700">{{ $stat['name'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>