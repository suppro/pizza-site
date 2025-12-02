<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Панель управления</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-blue-500 mx-auto rounded-full"></div>
        </div>

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Всего заказов</p>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Новые заказы</p>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['new_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Активные заказы</p>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['active_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Пользователей</p>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние заказы -->
<div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900">Последние заказы</h2>
    
    @if($recent_orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <th class="text-left py-4 px-6 font-bold text-gray-700">ID</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700">Клиент</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700">Статус</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700">Сумма</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700">Дата</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent_orders as $order)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                        <td class="py-4 px-6 font-bold">#{{ $order->id }}</td>
                        <td class="py-4 px-6 font-semibold">{{ $order->customer_name }}</td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1.5 rounded-xl text-xs font-bold shadow-md
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
                        </td>
                        <td class="py-4 px-6 font-bold text-lg">{{ number_format($order->total_amount, 2, '.', ' ') }} ₽</td>
                        <td class="py-4 px-6 text-gray-600">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="py-4 px-6">
                            <a href="{{ route('admin.order.detail', $order->id) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-bold hover:underline transition-colors">
                                Подробнее →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📦</div>
            <p class="text-gray-600 text-lg font-semibold">Заказов пока нет</p>
        </div>
    @endif
</div>
    </div>
</body>
</html>