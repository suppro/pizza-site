<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Пиццерия «Вжух!»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <!-- Шапка -->
    <header class="bg-red-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Вжух! Пицца</h1>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="bg-white text-red-600 px-6 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Войти
                </a>
            </div>
        </div>
    </header>

    <!-- Герой-секция -->
    <section class="bg-red-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-6">Самая вкусная пицца в городе!</h2>
            <p class="text-xl mb-8">Быстрая доставка, свежие ингредиенты, неповторимый вкус</p>
            <a href="{{ route('login') }}" class="bg-white text-red-600 px-8 py-4 rounded-lg text-xl font-bold hover:bg-gray-100">
                Заказать сейчас
            </a>
        </div>
    </section>

    <!-- Меню для гостей -->
    <main class="container mx-auto px-4 py-12">
        <h2 class="text-4xl font-bold text-center mb-12">Наше меню</h2>

        @foreach(\App\Models\Category::all() as $category)
            <section class="mb-16">
                <h3 class="text-3xl font-semibold mb-8 text-red-600">{{ $category->name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach(\App\Models\Product::where('category_id', $category->id)->where('is_active', 1)->get() as $product)
                        <div class="bg-white rounded-xl shadow-xl overflow-hidden hover:shadow-2xl transition">
                            @if($product->image)
                                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="w-full h-64 object-cover">
                            @else
                                <div class="bg-gray-200 h-64 flex items-center justify-center">
                                    <span class="text-gray-500">Фото скоро</span>
                                </div>
                            @endif

                            <div class="p-6">
                                <h4 class="text-2xl font-bold mb-2">{{ $product->name }}</h4>
                                <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                                <div class="text-center">
                                    <p class="text-lg font-semibold text-red-600 mb-2">От {{ $product->variants->min('price') }} ₽</p>
                                    <p class="text-sm text-gray-600">Войдите, чтобы заказать</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <!-- Призыв к действию -->
        <div class="text-center mt-16">
            <h3 class="text-3xl font-bold mb-4">Хотите заказать?</h3>
            <a href="{{ route('login') }}" class="bg-red-600 text-white px-8 py-4 rounded-lg text-xl font-bold hover:bg-red-700">
                Войдите в аккаунт
            </a>
        </div>
    </main>
</body>
</html>