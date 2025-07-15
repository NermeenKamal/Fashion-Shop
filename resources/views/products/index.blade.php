@extends('layouts.app')

@section('title', 'Fashion - Products')

@section('content')
<div class="products-header">
        <div>
        <h1 class="products-title">كل المنتجات</h1>
        <p class="products-desc">اكتشف مجموعتنا الرائعة من أزياء الموضة</p>
        </div>
    <div>
        <select class="products-sort" onchange="window.location.href=this.value">
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
            </select>
        </div>
    </div>
<div class="products-main-grid">
    <div class="products-filters">
        <div class="filters-title">فلترة</div>
        <div class="filters-section">
            <label class="filters-label">الفئات</label>
            @foreach($categories as $category)
            <div>
                <input type="checkbox" name="category" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }} onchange="applyFilters()" class="filters-checkbox">
                <span>{{ $category->name }}</span>
                <span class="category-count">{{ $category->products_count }}</span>
                </div>
                            @endforeach
                        </div>
        <div class="filters-section">
            <label class="filters-label">السعر الأدنى</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="filters-input" onchange="applyFilters()">
            <label class="filters-label">السعر الأعلى</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="1000" class="filters-input" onchange="applyFilters()">
                    </div>
                    @if(request('category') || request('min_price') || request('max_price') || request('search'))
        <button onclick="clearFilters()" class="filters-clear-btn">
            <i class="fas fa-times mr-2"></i>مسح الفلاتر
                    </button>
                    @endif
                </div>
    <div>
            @if($products->count() > 0)
        <div class="products-grid">
                    @php $wishlist = auth()->check() ? auth()->user()->wishlist->pluck('id')->toArray() : []; @endphp
                    @foreach($products as $product)
            @component('components.product-card', ['product' => $product])
            @endcomponent
                    @endforeach
                </div>
        <div class="mt-8">{{ $products->links() }}</div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-muted-foreground"></i>
                    </div>
            <h3 class="text-xl font-semibold mb-2">لا توجد منتجات</h3>
            <p class="text-muted mb-6">جرّب تغيير الفلاتر أو كلمات البحث</p>
            <button onclick="clearFilters()" class="filters-clear-btn">
                <i class="fas fa-refresh mr-2"></i>مسح الفلاتر
                    </button>
                </div>
            @endif
    </div>
</div>

<script>
function applyFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Add current sort
    const sort = new URLSearchParams(window.location.search).get('sort');
    if (sort) {
        const sortInput = document.createElement('input');
        sortInput.type = 'hidden';
        sortInput.name = 'sort';
        sortInput.value = sort;
        form.appendChild(sortInput);
    }

    // Add category filters
    const categoryCheckboxes = document.querySelectorAll('input[name="category"]:checked');
    categoryCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'category';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    // Add price filters
    const minPrice = document.querySelector('input[name="min_price"]').value;
    const maxPrice = document.querySelector('input[name="max_price"]').value;

    if (minPrice) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'min_price';
        input.value = minPrice;
        form.appendChild(input);
    }

    if (maxPrice) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'max_price';
        input.value = maxPrice;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function clearFilters() {
    window.location.href = window.location.pathname;
}
</script>

<style>
:root {
    --main-gold: #a98142;
    --main-gold-light: #c3b392;
    --main-gray: #f8f9fa;
    --main-white: #fff;
    --main-dark: #222;
}
body { background: var(--main-gray) !important; }
.products-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin-bottom: 32px !important;
}
.products-title {
    font-size: 2.1rem !important;
    font-weight: 800 !important;
    color: var(--main-gold) !important;
    margin-bottom: 0 !important;
}
.products-desc {
    color: #6c757d !important;
    font-size: 1.1rem !important;
    margin-top: 8px !important;
}
.products-sort {
    min-width: 180px !important;
    border-radius: 10px !important;
    border: 1.5px solid var(--main-gold-light) !important;
    padding: 10px 16px !important;
    font-size: 1rem !important;
    color: var(--main-dark) !important;
    background: var(--main-white) !important;
    font-weight: 600 !important;
    transition: border 0.2s !important;
}
.products-sort:focus {
    border-color: var(--main-gold) !important;
    outline: none !important;
}
.products-main-grid {
    display: grid !important;
    grid-template-columns: 1fr 3fr !important;
    gap: 40px !important;
}
@media (max-width: 1100px) {
    .products-main-grid { grid-template-columns: 1fr !important; }
}
.products-filters {
    background: var(--main-white) !important;
    border-radius: 16px !important;
    box-shadow: 0 2px 16px rgba(169,129,66,0.07) !important;
    padding: 32px 18px 24px 18px !important;
    border: 1.5px solid var(--main-gold-light) !important;
    margin-bottom: 32px !important;
}
.filters-title {
    font-size: 1.2rem !important;
    font-weight: 700 !important;
    color: var(--main-gold) !important;
    margin-bottom: 18px !important;
}
.filters-section {
    margin-bottom: 28px !important;
}
.filters-label {
    font-size: 1rem !important;
    color: var(--main-dark) !important;
    font-weight: 600 !important;
    margin-bottom: 8px !important;
    display: block !important;
}
.filters-checkbox {
    accent-color: var(--main-gold) !important;
    width: 18px !important;
    height: 18px !important;
    margin-left: 8px !important;
}
.filters-input {
    border-radius: 8px !important;
    border: 1.5px solid var(--main-gold-light) !important;
    padding: 7px 12px !important;
    font-size: 1rem !important;
    color: var(--main-dark) !important;
    background: var(--main-gray) !important;
    margin-bottom: 8px !important;
    width: 100% !important;
    transition: border 0.2s !important;
}
.filters-input:focus {
    border-color: var(--main-gold) !important;
    outline: none !important;
}
.filters-clear-btn {
    background: var(--main-gold-light) !important;
    color: var(--main-dark) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 0 !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    width: 100% !important;
    margin-top: 10px !important;
    transition: background 0.2s, color 0.2s !important;
}
.filters-clear-btn:hover {
    background: var(--main-gold) !important;
    color: #fff !important;
}
.products-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)) !important;
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
@media (max-width: 900px) {
    .products-main-grid { grid-template-columns: 1fr !important; }
    .products-header { flex-direction: column !important; gap: 18px !important; }
}
@media (max-width: 600px) {
    .products-grid { grid-template-columns: 1fr !important; }
    .products-header { flex-direction: column !important; gap: 12px !important; }
}
</style>
@endsection 