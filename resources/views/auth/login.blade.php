<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — Вжух! Пицца</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Логотип -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-red-600">Вжух! Пицца</h1>
                <h2 class="text-2xl font-bold text-gray-900 mt-4">Вход в аккаунт</h2>
                <p class="text-gray-600 mt-2">Войдите для заказа пиццы</p>
            </div>

            <!-- Форма -->
            <div class="bg-white py-8 px-6 shadow rounded-lg">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-4">
                        <!-- Логин -->
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700">Логин *</label>
                            <input id="login" name="login" type="text" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ old('login') }}" placeholder="Введите ваш логин">
                            @error('login')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Пароль -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Пароль *</label>
                            <input id="password" name="password" type="password" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   placeholder="Введите ваш пароль">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Запомнить меня -->
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Запомнить меня
                            </label>
                        </div>
                    </div>

                    <!-- Кнопка входа -->
                    <button type="submit" 
                            class="w-full mt-6 bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 font-medium">
                        Войти
                    </button>

                    <!-- Ссылка на регистрацию -->
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Нет аккаунта? 
                            <a href="{{ route('register') }}" class="text-red-600 hover:text-red-500 font-medium">Зарегистрируйтесь</a>
                        </p>
                    </div>

                    <!-- Тестовые данные -->
                    <div class="mt-6 p-4 bg-gray-100 rounded-md">
                        <p class="text-sm text-gray-600 text-center">
                            <strong>Тестовый аккаунт:</strong><br>
                            Логин: admin<br>
                            Пароль: admin123
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>