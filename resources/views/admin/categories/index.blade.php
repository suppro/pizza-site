<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Категории — Админ-панель — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    use Illuminate\Support\Str;
@endphp
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    @include('admin.partials.header')

    <div class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Управление категориями</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full"></div>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                + Добавить категорию
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

        <!-- Таблица категорий -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold">ID</th>
                            <th class="px-6 py-4 text-left font-bold">Название</th>
                            <th class="px-6 py-4 text-left font-bold">Родительская категория</th>
                            <th class="px-6 py-4 text-left font-bold">Товаров</th>
                            <th class="px-6 py-4 text-left font-bold">Подкатегорий</th>
                            <th class="px-6 py-4 text-left font-bold">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($categories->whereNull('parent_id') as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold">{{ $category->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $category->name }}</div>
                                    @if($category->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-400">—</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                        {{ $category->products->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-semibold">
                                        {{ $category->children->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 font-semibold text-sm transition-colors">
                                            Редактировать
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                              onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 font-semibold text-sm transition-colors">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @foreach($category->children as $child)
                                <tr class="hover:bg-gray-50 transition-colors bg-gray-50">
                                    <td class="px-6 py-4 font-semibold pl-12">{{ $child->id }}</td>
                                    <td class="px-6 py-4 pl-12">
                                        <div class="font-bold text-gray-900">— {{ $child->name }}</div>
                                        @if($child->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($child->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold">
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                            {{ $child->products->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400">—</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.categories.edit', $child->id) }}" 
                                               class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 font-semibold text-sm transition-colors">
                                                Редактировать
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" 
                                                  onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 font-semibold text-sm transition-colors">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

