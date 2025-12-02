<div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
    <div class="relative overflow-hidden">
        @if($product->mainImage)
            <img src="{{ $product->mainImage->url }}" alt="{{ $product->name }}"
                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-gradient-to-br from-gray-100 to-gray-200 h-64 flex items-center justify-center\'><span class=\'text-gray-400 text-lg\'>Фото скоро</span></div>'">
        @else
            <div class="bg-gradient-to-br from-gray-100 to-gray-200 h-64 flex items-center justify-center">
                <span class="text-gray-400 text-lg">Фото скоро</span>
            </div>
        @endif
        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
            <span class="text-blue-600 font-bold">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
        </div>
        @if($product->stock_quantity > 0)
            <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                В наличии: {{ $product->stock_quantity }}
            </div>
        @else
            <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                Нет в наличии
            </div>
        @endif
    </div>

    <div class="p-6">
        <a href="{{ route('product.show', $product->slug) }}" class="block">
            <h4 class="text-2xl font-bold mb-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $product->name }}</h4>
        </a>
        @if($product->sku)
            <p class="text-sm text-gray-500 mb-3">Артикул: <strong class="font-mono">{{ $product->sku }}</strong></p>
        @endif
        <p class="text-gray-600 mb-4 leading-relaxed">{{ Str::limit($product->description, 100) }}</p>
        
        @if($product->technical_specs && count($product->technical_specs) > 0)
            <div class="mb-4">
                <p class="text-xs text-gray-500 mb-2">Основные характеристики:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(array_slice($product->technical_specs, 0, 2) as $key => $value)
                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $key }}: {{ $value }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($product->stock_quantity > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="flex items-center gap-2">
                    <label for="quantity_{{ $product->id }}" class="text-sm font-semibold text-gray-700">Количество:</label>
                    <input type="number" name="quantity" id="quantity_{{ $product->id }}" value="1" min="1" max="{{ $product->stock_quantity }}"
                           class="w-20 px-3 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                </div>
                
                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3.5 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold whitespace-nowrap shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    🛒 В корзину
                </button>
            </form>
        @else
            <p class="text-sm text-red-500 text-center py-4 font-semibold">Товар временно недоступен</p>
        @endif
    </div>
</div>

