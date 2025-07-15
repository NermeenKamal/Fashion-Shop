<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow">
    <div class="relative">
        @auth
        <button type="button" class="wishlist-btn absolute top-3 right-3 w-8 h-8 bg-white text-red-500 rounded-full flex items-center justify-center shadow hover:bg-red-50 transition-colors" data-id="{{ $product->id }}" title="أضف إلى المفضلة">
            <i class="fas fa-heart" style="color:{{ (auth()->user()->wishlist->contains($product->id) ? '#a98142' : '#ccc') }};"></i>
        </button>
        @endauth
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
        @if($product->category)
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $product->category->name }}
            </span>
        </div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
        <div class="flex items-center justify-between mb-4">
            <div>
                @if($product->discount_price)
                    <span class="text-lg font-bold text-red-600">{{ number_format($product->discount_price, 2) }} د.ل</span>
                    <span class="text-sm text-gray-500 line-through mr-2">{{ number_format($product->price, 2) }} د.ل</span>
                @else
                    <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 2) }} د.ل</span>
                @endif
            </div>
            @if($product->stock > 0)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle ml-1"></i>متوفر
                </span>
            @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle ml-1"></i>غير متوفر
                </span>
            @endif
        </div>
        <div class="flex space-x-2 space-x-reverse">
            <a href="{{ route('products.show', $product) }}" class="flex-1 bg-blue-600 text-white text-center py-2 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                <i class="fas fa-eye ml-1"></i>عرض
            </a>
            @if($product->stock > 0)
                <form action="{{ route('cart.add.product', $product) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white text-center py-2 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-shopping-cart ml-1"></i>إضافة للسلة
                    </button>
                </form>
            @endif
        </div>
    </div>
</div> 