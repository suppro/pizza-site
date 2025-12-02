<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Интеграция с 1С — Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Интеграция с 1С</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full"></div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-xl mb-6">
                {{ session('info') }}
            </div>
        @endif

        <!-- Статус интеграции -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Статус интеграции</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                    <div class="text-3xl font-extrabold text-blue-600 mb-2">{{ $totalOrders }}</div>
                    <div class="text-gray-700 font-semibold">Всего заказов</div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                    <div class="text-3xl font-extrabold text-green-600 mb-2">{{ $syncedOrders }}</div>
                    <div class="text-gray-700 font-semibold">Выгружено в 1С</div>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border-2 border-yellow-200">
                    <div class="text-3xl font-extrabold text-yellow-600 mb-2">{{ $pendingOrders }}</div>
                    <div class="text-gray-700 font-semibold">Ожидают выгрузки</div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    @if($isEnabled)
                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        <span class="font-bold text-green-700">Интеграция включена</span>
                    @else
                        <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                        <span class="font-bold text-red-700">Интеграция отключена</span>
                    @endif
                </div>
            </div>

            @if(!$isEnabled)
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <p class="text-yellow-800 font-semibold">
                        ⚠️ Для включения интеграции установите <code class="bg-yellow-100 px-2 py-1 rounded">ONEC_ENABLED=true</code> в файле .env
                    </p>
                </div>
            @endif
        </div>

        <!-- Действия -->
        @if($isEnabled && $pendingOrders > 0)
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Действия</h2>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('admin.onec.export-all') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            📤 Выгрузить все невыгруженные заказы ({{ $pendingOrders }})
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Невыгруженные заказы -->
        @if($pendingOrders > 0)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b-2 border-gray-100">
                    <h2 class="text-2xl font-extrabold text-gray-900">Заказы, ожидающие выгрузки</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold">ID</th>
                                <th class="px-6 py-4 text-left font-bold">Клиент</th>
                                <th class="px-6 py-4 text-left font-bold">Статус</th>
                                <th class="px-6 py-4 text-left font-bold">Сумма</th>
                                <th class="px-6 py-4 text-left font-bold">Дата</th>
                                <th class="px-6 py-4 text-left font-bold">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach(\App\Models\Order::where('is_synced_to_1c', false)
                                ->whereIn('status', ['new', 'processing'])
                                ->orderBy('created_at', 'desc')
                                ->take(20)
                                ->get() as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->customer_phone ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-lg text-sm font-semibold
                                            @if($order->status == 'new') bg-yellow-100 text-yellow-700
                                            @else bg-blue-100 text-blue-700 @endif">
                                            {{ $order->status == 'new' ? 'Новый' : 'В обработке' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.onec.export', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold text-sm transition-colors">
                                                Выгрузить
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.order.detail', $order->id) }}" 
                                           class="ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold text-sm transition-colors">
                                            Детали
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($pendingOrders == 0)
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center border border-gray-100">
                <div class="text-6xl mb-4">✅</div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Все заказы выгружены</h3>
                <p class="text-gray-600">Нет заказов, ожидающих выгрузки в 1С</p>
            </div>
        @endif
    </div>
</body>
</html>

