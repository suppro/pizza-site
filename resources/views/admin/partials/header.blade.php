<!-- Админ-шапка -->
<header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-xl sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
    <div class="container mx-auto px-4 py-5 flex justify-between items-center">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="text-3xl font-extrabold tracking-tight hover:scale-105 transition-transform duration-200">
                ⚙️ АО «Арвиай» — Админ
            </a>
            <nav class="hidden md:flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="hover:text-blue-200 font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white px-4 py-2 rounded-xl' : '' }}">
                    Дашборд
                </a>
                <a href="{{ route('admin.orders') }}" 
                   class="hover:text-blue-200 font-semibold transition-colors {{ request()->routeIs('admin.orders') || request()->routeIs('admin.order.detail') ? 'bg-blue-500 text-white px-4 py-2 rounded-xl' : '' }}">
                    Заказы
                </a>
                <a href="{{ route('admin.products.index') }}" 
                   class="hover:text-blue-200 font-semibold transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-blue-500 text-white px-4 py-2 rounded-xl' : '' }}">
                    Товары
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="hover:text-blue-200 font-semibold transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-blue-500 text-white px-4 py-2 rounded-xl' : '' }}">
                    Категории
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="hover:text-blue-200 font-semibold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-500 text-white px-4 py-2 rounded-xl' : '' }}">
                    Пользователи
                </a>
                <a href="{{ route('admin.onec.index') }}" 
                   class="hover:text-blue-200 font-semibold transition-colors {{ request()->routeIs('admin.onec.*') ? 'bg-blue-500 text-white px-4 py-2 rounded-xl' : '' }}">
                    1С
                </a>
            </nav>
        </div>
        <div class="flex items-center gap-4">
            <span class="hidden md:block text-blue-100 font-semibold">Админ: {{ auth()->user()->name ?? 'Администратор' }}</span>
            <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                Клиентская часть
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2.5 rounded-xl font-bold hover:bg-red-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">Выйти</button>
            </form>
        </div>
    </div>
</header>

