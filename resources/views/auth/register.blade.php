<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация — Вжух! Пицца</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Логотип -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-red-600">Вжух! Пицца</h1>
                <h2 class="text-2xl font-bold text-gray-900 mt-4">Создать аккаунт</h2>
                <p class="text-gray-600 mt-2">Зарегистрируйтесь для заказа пиццы</p>
            </div>

            <!-- Форма -->
            <div class="bg-white py-8 px-6 shadow rounded-lg">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="space-y-4">
                        <!-- ФИО -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">ФИО *</label>
                            <input id="name" name="name" type="text" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ old('name') }}" placeholder="Иван Иванов">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Телефон -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Телефон *</label>
                            <input id="phone" name="phone" type="tel" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ old('phone') }}" placeholder="+79991234567">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email (необязательно)</label>
                            <input id="email" name="email" type="email" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ old('email') }}" placeholder="ivan@example.com">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Логин -->
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700">Логин *</label>
                            <input id="login" name="login" type="text" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ old('login') }}" placeholder="Придумайте логин">
                            @error('login')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Адрес -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Адрес доставки</label>
                            <input id="address" name="address" type="text" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   value="{{ old('address') }}" placeholder="ул. Ленина, д. 10, кв. 25">
                            @error('address')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Пароль -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Пароль *</label>
                            <input id="password" name="password" type="password" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   placeholder="Не менее 6 символов">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтвердите пароль *</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                   placeholder="Повторите пароль">
                        </div>
                    </div>

                    <!-- Кнопка регистрации -->
                    <button type="submit" 
                            class="w-full mt-6 bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 font-medium">
                        Зарегистрироваться
                    </button>

                    <!-- Ссылка на вход -->
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Уже есть аккаунт? 
                            <a href="{{ route('login') }}" class="text-red-600 hover:text-red-500 font-medium">Войдите</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>