<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Пиццерия «Вжух!»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">

    <!-- Шапка -->
    <header class="bg-gradient-to-r from-red-600 to-red-700 text-white shadow-xl sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
        <div class="container mx-auto px-4 py-5 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-3xl font-extrabold tracking-tight hover:scale-105 transition-transform duration-200">
                🍕 Вжух! Пицца
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('register') }}" class="text-white hover:text-yellow-200 font-semibold transition-colors duration-200">
                    Регистрация
                </a>
                <a href="{{ route('login') }}" class="bg-white text-red-600 px-6 py-2.5 rounded-xl font-bold hover:bg-yellow-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    Войти
                </a>
            </div>
        </div>
    </header>

    <!-- Герой-секция -->
    <section class="relative bg-gradient-to-br from-red-600 via-red-700 to-orange-600 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-300 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-6xl md:text-7xl font-extrabold mb-6 leading-tight">
                Самая вкусная пицца<br>в городе!
            </h2>
            <p class="text-xl md:text-2xl mb-10 text-red-50 font-medium">Быстрая доставка, свежие ингредиенты, неповторимый вкус</p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-red-600 px-10 py-4 rounded-2xl text-xl font-bold hover:bg-yellow-50 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                Заказать сейчас →
            </a>
        </div>
    </section>

    <!-- Меню для гостей -->
    <main class="container mx-auto px-4 py-16">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-extrabold text-gray-900 mb-4">Наше меню</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-red-600 to-orange-500 mx-auto rounded-full"></div>
        </div>

        @foreach(\App\Models\Category::orderByRaw("FIELD(name, 'Пицца', 'Закуски', 'Десерты', 'Напитки')")->get() as $category)
            <section class="mb-20">
                <h3 class="text-4xl font-bold mb-10 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600">
                    {{ $category->name }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach(\App\Models\Product::where('category_id', $category->id)->where('is_active', 1)->get() as $product)
                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <div class="relative overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 h-64 flex items-center justify-center">
                                        <span class="text-gray-400 text-lg">Фото скоро</span>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                    <span class="text-red-600 font-bold">От {{ $product->variants->min('price') }} ₽</span>
                                </div>
                            </div>

                            <div class="p-6">
                                <h4 class="text-2xl font-bold mb-3 text-gray-900 group-hover:text-red-600 transition-colors">{{ $product->name }}</h4>
                                <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->description }}</p>
                                <div class="text-center pt-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-500 font-medium">Войдите, чтобы заказать</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <!-- Призыв к действию -->
        <div class="text-center mt-20 py-16 bg-gradient-to-r from-red-600 to-orange-600 rounded-3xl shadow-2xl">
            <h3 class="text-4xl font-extrabold text-white mb-6">Хотите заказать?</h3>
            <p class="text-xl text-red-50 mb-8">Войдите в аккаунт и начните заказывать прямо сейчас!</p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-red-600 px-10 py-4 rounded-2xl text-xl font-bold hover:bg-yellow-50 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                Войдите в аккаунт →
            </a>
        </div>
    </main>
</body>
</html>