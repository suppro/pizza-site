<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редактировать пользователя — Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-4 inline-block">
                ← Назад к списку пользователей
            </a>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Редактировать пользователя</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 max-w-2xl">
            <form action="{{ route('admin.users.update', $editUser->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Имя *</label>
                        <input type="text" name="name" value="{{ old('name', $editUser->name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $editUser->email) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Телефон</label>
                        <input type="tel" name="phone" value="{{ old('phone', $editUser->phone) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Новый пароль</label>
                        <input type="password" name="password"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        <p class="text-sm text-gray-500 mt-1">Оставьте пустым, чтобы не менять пароль</p>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Подтверждение пароля</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Роль *</label>
                        <select name="role" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="client" {{ old('role', $editUser->role) == 'client' ? 'selected' : '' }}>Клиент</option>
                            <option value="admin" {{ old('role', $editUser->role) == 'admin' ? 'selected' : '' }}>Администратор</option>
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 font-bold transition-colors">
                        Отмена
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

