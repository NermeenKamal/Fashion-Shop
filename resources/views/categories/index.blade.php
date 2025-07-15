@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold">Shop by Category</h1>
        <p class="text-muted mt-2">Browse our products by category to find exactly what you're looking for</p>
    </div>

    <!-- Categories Grid -->
    <div class="categories-grid">
        @foreach($categories as $category)
        <a href="{{ route('categories.show', $category) }}" class="category-card">
            <div class="category-icon"><i class="fas fa-tshirt"></i></div>
            <div class="category-title">{{ $category->name }}</div>
            <div class="category-desc">{{ $category->description }}</div>
            <div class="category-count">{{ $category->products_count }} منتج</div>
        </a>
        @endforeach
    </div>

    @if($categories->count() == 0)
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-tags text-3xl text-muted-foreground"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">No Categories Available</h3>
        <p class="text-muted">Categories will appear here once they're added to the store.</p>
    </div>
    @endif

    <!-- Featured Products Section -->
    <div class="mt-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold">Featured Products</h2>
            <p class="text-muted">Check out our most popular items</p>
        </div>
        
        <div class="products-grid">
            @php $wishlist = auth()->check() ? auth()->user()->wishlist->pluck('id')->toArray() : []; @endphp
            @foreach($featuredProducts ?? [] as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        
        @if(empty($featuredProducts ?? []))
        <div class="text-center py-8">
            <p class="text-muted">No featured products available at the moment.</p>
        </div>
        @endif
    </div>
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
@endsection 

@push('styles')
<style>
:root {
    --main-gold: #a98142;
    --main-gold-light: #c3b392;
    --main-gray: #f8f9fa;
    --main-white: #fff;
    --main-dark: #222;
}
body { background: var(--main-gray) !important; }
.categories-header {
    text-align: center !important;
    margin-bottom: 40px !important;
}
.categories-title {
    font-size: 2.1rem !important;
    font-weight: 800 !important;
    color: var(--main-gold) !important;
    margin-bottom: 0 !important;
}
.categories-desc {
    color: #6c757d !important;
    font-size: 1.1rem !important;
    margin-top: 8px !important;
}
.categories-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
    gap: 32px !important;
    margin-bottom: 48px !important;
}
.category-card {
    background: var(--main-white) !important;
    border-radius: 18px !important;
    box-shadow: 0 2px 16px rgba(169,129,66,0.07) !important;
    padding: 28px 18px 20px 18px !important;
    text-align: center !important;
    transition: box-shadow 0.2s, transform 0.2s !important;
    border: 1.5px solid var(--main-gold-light) !important;
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
}
.category-card:hover {
    box-shadow: 0 8px 32px rgba(169,129,66,0.13) !important;
    border-color: var(--main-gold) !important;
    transform: translateY(-4px) scale(1.03) !important;
}
.category-icon {
    width: 60px !important;
    height: 60px !important;
    background: var(--main-gold-light) !important;
    color: var(--main-gold) !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 2rem !important;
    margin: 0 auto 16px auto !important;
    transition: background 0.2s, color 0.2s !important;
}
.category-card:hover .category-icon {
    background: var(--main-gold) !important;
    color: #fff !important;
}
.category-title {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    color: var(--main-dark) !important;
    margin-bottom: 6px !important;
}
.category-desc {
    color: #6c757d !important;
    font-size: 0.97rem !important;
    margin-bottom: 10px !important;
    min-height: 36px !important;
}
.category-count {
    color: #a98142cc !important;
    font-size: 0.97rem !important;
    margin-bottom: 0 !important;
}
.category-link {
    color: var(--main-gold) !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: color 0.2s !important;
    font-size: 1.1rem !important;
}
.category-link:hover {
    color: var(--main-gold-light) !important;
}
.products-title {
    font-size: 1.7rem !important;
    font-weight: 800 !important;
    color: var(--main-gold) !important;
    margin-bottom: 0 !important;
}
.products-desc {
    color: #6c757d !important;
    font-size: 1.1rem !important;
    margin-top: 8px !important;
}
.products-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
    gap: 32px !important;
}
.product-card {
    background: var(--main-white) !important;
    border-radius: 18px !important;
    box-shadow: 0 2px 16px rgba(169,129,66,0.07) !important;
    padding: 18px 14px 18px 14px !important;
    text-align: center !important;
    transition: box-shadow 0.2s, transform 0.2s !important;
    border: 1.5px solid var(--main-gold-light) !important;
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
}
.product-card:hover {
    box-shadow: 0 8px 32px rgba(169,129,66,0.13) !important;
    border-color: var(--main-gold) !important;
    transform: translateY(-4px) scale(1.03) !important;
}
.product-img-box {
    width: 100% !important;
    aspect-ratio: 1/1.25 !important;
    background: var(--main-gray) !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 14px !important;
    overflow: hidden !important;
}
.product-img {
    width: 100% !important;
    height: 180px !important;
    object-fit: cover !important;
    border-radius: 12px !important;
    background: #fafafa !important;
    transition: transform 0.2s !important;
}
.product-card:hover .product-img {
    transform: scale(1.04) !important;
}
.product-title {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    color: var(--main-dark) !important;
    margin-bottom: 6px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.product-desc {
    color: #6c757d !important;
    font-size: 0.97rem !important;
    margin-bottom: 10px !important;
    min-height: 36px !important;
}
.product-price-row {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    justify-content: center !important;
    margin-bottom: 12px !important;
}
.product-old-price {
    color: #888 !important;
    text-decoration: line-through !important;
    font-size: 1rem !important;
}
.product-sale-price {
    color: var(--main-gold) !important;
    font-weight: 700 !important;
    font-size: 1.08rem !important;
}
.product-badge {
    position: absolute !important;
    top: 14px !important;
    left: 14px !important;
    background: var(--main-gold) !important;
    color: #fff !important;
    border-radius: 8px !important;
    padding: 5px 14px !important;
    font-size: 0.95rem !important;
    font-weight: 700 !important;
    z-index: 2 !important;
}
.product-actions {
    display: flex !important;
    gap: 8px !important;
    margin-top: auto !important;
    justify-content: center !important;
}
.product-btn {
    flex: 1 1 0 !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 10px 0 !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s !important;
    cursor: pointer !important;
    text-align: center !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
}
.product-btn.details {
    background: linear-gradient(135deg, var(--main-gold) 0%, var(--main-gold-light) 100%) !important;
    color: #fff !important;
}
.product-btn.details:hover {
    background: linear-gradient(135deg, var(--main-gold-light) 0%, var(--main-gold) 100%) !important;
}
.product-btn.cart {
    background: var(--main-gray) !important;
    color: var(--main-gold) !important;
    border: 2px solid var(--main-gold) !important;
}
.product-btn.cart:hover {
    background: var(--main-gold) !important;
    color: #fff !important;
}
.wishlist-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
    outline: inherit;
    color: var(--main-gold);
}
@media (max-width: 900px) {
    .categories-grid, .products-grid { grid-template-columns: 1fr 1fr !important; }
}
@media (max-width: 600px) {
    .categories-grid, .products-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush 