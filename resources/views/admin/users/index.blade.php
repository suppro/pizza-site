<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Пользователи — Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Управление пользователями</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full"></div>
            </div>
            <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                + Добавить пользователя
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Фильтры -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Поиск по имени, email или телефону..."
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                </div>
                <div>
                    <select name="role" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        <option value="">Все роли</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Администраторы</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Клиенты</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 font-bold transition-colors">
                        Применить
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-300 font-bold transition-colors">
                        Сбросить
                    </a>
                </div>
            </form>
        </div>

        <!-- Таблица пользователей -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold">ID</th>
                            <th class="px-6 py-4 text-left font-bold">Имя</th>
                            <th class="px-6 py-4 text-left font-bold">Email</th>
                            <th class="px-6 py-4 text-left font-bold">Телефон</th>
                            <th class="px-6 py-4 text-left font-bold">Роль</th>
                            <th class="px-6 py-4 text-left font-bold">Дата регистрации</th>
                            <th class="px-6 py-4 text-left font-bold">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold">{{ $user->id }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->phone ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role == 'admin')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-semibold">Администратор</span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">Клиент</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 font-semibold text-sm transition-colors">
                                            Редактировать
                                        </a>
                                        @if($user->id != auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                                  onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 font-semibold text-sm transition-colors">
                                                    Удалить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Пользователи не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>

