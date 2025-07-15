@extends('layouts.app')

@section('title', 'Fashion - ' . $product->name)

@section('content')
<div class="space-y-8">
    <!-- Product Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="space-y-4">
            <div class="aspect-square bg-muted rounded-lg overflow-hidden" style="position:relative;">
                @auth
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" style="position:absolute;top:10px;left:10px;z-index:2;">
                    @csrf
                    <button type="submit" class="wishlist-btn" title="أضف إلى المفضلة">
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
                @endauth
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>
            
            @if($product->discount_price)
                <div class="flex justify-center">
                    <span class="badge badge-destructive text-lg px-4 py-2">
                        {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                    </span>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-sm text-muted">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    <span class="text-muted">•</span>
                    <span class="text-sm text-muted">SKU: {{ $product->sku ?? 'N/A' }}</span>
                </div>
                
                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                
                @if($product->stock > 0)
                    <span class="badge badge-success">
                        <i class="fas fa-check mr-1"></i>
                        متوفر ({{ $product->stock }})
                    </span>
                @else
                    <span class="badge badge-destructive">
                        <i class="fas fa-times mr-1"></i>
                        غير متوفر
                    </span>
                @endif
            </div>

            <!-- Price -->
            <div class="space-y-2">
                @if($product->discount_price)
                    <div class="flex items-center gap-3">
                        <span class="text-3xl font-bold text-primary">{{ number_format($product->discount_price, 2) }} د.ل</span>
                        <span class="text-xl text-muted line-through">{{ number_format($product->price, 2) }} د.ل</span>
                        <span class="badge badge-destructive">
                            وفر {{ number_format($product->price - $product->discount_price, 2) }} د.ل
                        </span>
                    </div>
                @else
                    <span class="text-3xl font-bold text-primary">{{ number_format($product->price, 2) }} د.ل</span>
                @endif
            </div>

            <!-- Description -->
            <div>
                <h3 class="font-semibold text-lg mb-2">Description</h3>
                <p class="text-muted leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Product Details -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-muted">Brand</span>
                    <p class="font-medium">{{ $product->brand ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm text-muted">Material</span>
                    <p class="font-medium">{{ $product->material ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm text-muted">Color</span>
                    <p class="font-medium">{{ $product->color ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm text-muted">Size</span>
                    <p class="font-medium">{{ $product->size ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Add to Cart -->
            @if($product->stock > 0)
                <div class="space-y-4">
                    @auth
                        <form action="{{ route('cart.add.product', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-medium">الكمية:</label>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="input w-20">
                                <span class="text-sm text-muted">({{ $product->stock }} متوفر)</span>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="product-btn cart"><i class="fas fa-cart-plus ms-1"></i>أضف للسلة</button>
                                @php
                                    $inWishlist = auth()->check() && auth()->user()->wishlist->contains($product->id);
                                @endphp
                                <button id="wishlist-btn" type="button" class="product-btn details" style="display:flex;align-items:center;gap:6px;">
                                    <i id="wishlist-heart" class="fas fa-heart" style="color:{{ $inWishlist ? '#a98142' : '#ccc' }};"></i>المفضلة
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-medium">الكمية:</label>
                                <input type="number" value="1" min="1" max="{{ $product->stock }}" class="input w-20" disabled>
                                <span class="text-sm text-muted">({{ $product->stock }} متوفر)</span>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('login') }}" class="product-btn cart"><i class="fas fa-sign-in-alt ms-1"></i>سجّل للشراء</a>
                                <a href="{{ route('profile.wishlist') }}" class="product-btn details"><i class="fas fa-heart ms-1"></i>المفضلة</a>
                            </div>
                        </div>
                    @endauth
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-muted mb-4">هذا المنتج غير متوفر حالياً.</p>
                    <button type="button" class="product-btn details">
                        <i class="fas fa-bell ms-1"></i>
                        أعلمني عند التوفر
                    </button>
                </div>
            @endif

            <!-- Shipping Info -->
            <div class="card">
                <div class="card-content p-4">
                    <h3 class="font-semibold mb-3">Shipping Information</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-truck text-primary"></i>
                            <span>Free shipping on orders over $50</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-undo text-primary"></i>
                            <span>30-day return policy</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-primary"></i>
                            <span>Secure payment processing</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold">Related Products</h2>
            <p class="text-muted">You might also like these products</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php $wishlist = auth()->check() ? auth()->user()->wishlist->pluck('id')->toArray() : []; @endphp
            @foreach($relatedProducts as $relatedProduct)
                @component('components.product-card', ['product' => $relatedProduct])
                @endcomponent
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-square {
    aspect-ratio: 1 / 1;
}
</style>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('wishlist-btn');
    const heart = document.getElementById('wishlist-heart');
    const msg = document.getElementById('wishlist-msg');
    let inWishlist = {{ $inWishlist ? 'true' : 'false' }};
    btn.addEventListener('click', function() {
        fetch("{{ route('wishlist.toggle', $product) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                inWishlist = data.in_wishlist;
                heart.style.color = inWishlist ? '#a98142' : '#ccc';
                msg.textContent = data.message;
                msg.style.display = 'inline';
                setTimeout(() => { msg.style.display = 'none'; }, 2000);
            } else {
                msg.textContent = 'حدث خطأ!';
                msg.style.display = 'inline';
            }
        })
        .catch(() => {
            msg.textContent = 'حدث خطأ!';
            msg.style.display = 'inline';
        });
    });
});
</script>
@endauth
@endsection 