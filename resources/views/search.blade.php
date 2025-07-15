@extends('layouts.app')

@section('title', 'Fashion - Search Results')

@section('content')
<!-- Search Header -->
<div class="search-header bg-light py-4 mb-4 rounded">
    <div class="container">
        <h1 class="mb-3">نتائج البحث</h1>
        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <input type="text" class="input flex-1 text-lg" name="q" value="{{ $query }}" placeholder="ابحث في المنتجات...">
            <button type="submit" class="btn btn-primary text-lg flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> بحث
            </button>
        </form>
        @if($query)
            <p class="text-muted mt-3 mb-0">
                تم العثور على {{ $products->total() }} منتج{{ $products->total() > 1 ? 'ات' : '' }} لـ "{{ $query }}"
            </p>
        @endif
    </div>
</div>

<!-- Search Results -->
<div class="container">
    @if($products->count() > 0)
        <div class="search-grid">
            @foreach($products as $product)
                @component('components.product-card', ['product' => $product])
                @endcomponent
            @endforeach
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(['q' => $query])->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h3>لا توجد نتائج</h3>
            <p class="text-muted">
                لم نتمكن من العثور على منتجات تطابق "{{ $query }}"
            </p>
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary me-3">
                    <i class="fas fa-home me-2"></i>العودة للرئيسية
                </a>
                <a href="{{ route('category', 'women-clothing') }}" class="btn btn-outline-primary me-2">
                    ملابس نسائية
                </a>
                <a href="{{ route('category', 'men-clothing') }}" class="btn btn-outline-primary me-2">
                    ملابس رجالية
                </a>
                <a href="{{ route('category', 'kids-clothing') }}" class="btn btn-outline-primary">
                    ملابس أطفال
                </a>
            </div>
        </div>
    @endif
</div>
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
.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 28px;
}
.search-card {
    background: var(--main-white);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(169,129,66,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s;
    position: relative;
    border: 1.5px solid var(--main-gold-light);
}
.search-card:hover {
    box-shadow: 0 8px 32px rgba(169,129,66,0.13);
    border-color: var(--main-gold);
}
.search-card-img-box {
    width: 100%;
    aspect-ratio: 1/1.25;
    background: var(--main-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.search-card-img {
    max-width: 100%;
    max-height: 100%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0 0 12px 12px;
    transition: transform 0.2s;
    background: #fafafa;
}
.search-card-img:hover {
    transform: scale(1.04);
}
.search-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #fff;
    z-index: 2;
}
.search-badge.sale { background: var(--main-gold); right: 12px; left: auto; }
.search-badge.stock { background: var(--main-gold-light); color: var(--main-dark); left: 12px; right: auto; }
.search-badge.out { background: #b0b0b0; left: 12px; right: auto; }
.search-card-body {
    padding: 18px 16px 16px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.search-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--main-dark);
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.search-card-desc {
    color: #6c757d;
    font-size: 0.97rem;
    margin-bottom: 10px;
    min-height: 36px;
}
.search-card-price-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}
.search-card-old-price {
    color: #888;
    text-decoration: line-through;
    font-size: 1rem;
}
.search-card-sale-price {
    color: var(--main-gold);
    font-weight: 700;
    font-size: 1.08rem;
}
.search-card-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
}
.search-btn {
    flex: 1 1 0;
    border: none;
    border-radius: 10px;
    padding: 10px 0;
    font-size: 1rem;
    font-weight: 600;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.search-btn.details {
    background: linear-gradient(135deg, var(--main-gold) 0%, var(--main-gold-light) 100%);
    color: #fff;
}
.search-btn.details:hover {
    background: linear-gradient(135deg, var(--main-gold-light) 0%, var(--main-gold) 100%);
}
.search-btn.cart {
    background: var(--main-gray);
    color: var(--main-gold);
    border: 2px solid var(--main-gold);
}
.search-btn.cart:hover {
    background: var(--main-gold);
    color: #fff;
}
@media (max-width: 700px) {
    .search-grid {
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
}
@media (max-width: 500px) {
    .search-grid {
        grid-template-columns: 1fr;
    }
    .search-card-body {
        padding: 12px 8px 10px 8px;
    }
}
</style>
@endpush 