<header class="bg-red-600 text-white shadow-lg">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-3xl font-bold">Вжух! Пицца</a>
        <div class="flex items-center gap-6">
            @auth
                <span class="hidden md:block">Привет, {{ auth()->user()->name }}!</span>
                <a href="{{ route('cart') }}" class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-100 relative">
                    Корзина
                    @if(session('cart') && collect(session('cart'))->sum('quantity') > 0)
                        <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-xs rounded-full w-6 h-6 flex items-center justify-center">
                            {{ collect(session('cart'))->sum('quantity') }}
                        </span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="hover:underline">Выйти</button>
                </form>
            @endauth
        </div>
    </div>
</header>
    <div class="bg-yellow-400 text-black p-2 text-center font-bold">
        DEBUG: @auth ЗАЛОГИНЕН как {{ auth()->user()->login }} @else НЕ ЗАЛОГИНЕН @endauth
    </div>
