<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Вход в личный кабинет</h2>
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
                        <input id="login" name="login" type="text" required autofocus
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                               value="{{ old('login') }}" placeholder="admin">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                        <input id="password" name="password" type="password" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                               placeholder="admin123">
                    </div>
                </div>

                @error('login')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="h-4 w-4 text-red-600 rounded">
                        <span class="ml-2 text-sm text-gray-600">Запомнить меня</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Войти
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>