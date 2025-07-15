@extends('layouts.app')

@section('title', 'Fashion - Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section-modern">
    <div class="hero-bg-pattern"></div>
    <div class="container hero-content">
        <div class="hero-text-box">
            <h1 class="hero-title">اكتشف أناقتك</h1>
            <p class="hero-desc">تسوق أحدث صيحات الموضة، ملابس عصرية، إكسسوارات أنيقة، وكل ما تحتاجه لإبراز أسلوبك الخاص.</p>
            <div class="hero-btns">
                <a href="{{ route('products.index') }}" class="hero-btn-main"><i class="fas fa-shopping-bag ms-2"></i>تسوق الآن</a>
                <a href="{{ route('categories.index') }}" class="hero-btn-secondary"><i class="fas fa-tags ms-2"></i>تصفح الفئات</a>
            </div>
        </div>
        <div class="hero-img-box">
            <img src="{{ asset('images/logo.png') }}" alt="Fashion" class="hero-img">
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section-space">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">تسوق حسب الفئة</h2>
            <a href="{{ route('categories.index') }}" class="section-link">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
    </div>
        <div class="category-grid">
        @foreach($categories ?? [] as $category)
            <a href="{{ route('categories.show', $category) }}" class="category-card">
                <div class="category-icon"><i class="fas fa-tshirt"></i></div>
                <div class="category-title">{{ $category->name }}</div>
                <div class="category-count">{{ $category->products_count ?? 0 }} منتج</div>
        </a>
        @endforeach
        @if(empty($categories ?? []))
        <div class="col-span-full text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 bg-muted rounded-full flex items-center justify-center">
                <i class="fas fa-tags text-3xl text-muted-foreground"></i>
            </div>
                <h3 class="text-xl font-semibold mb-2">لا توجد فئات متاحة</h3>
                <p class="text-muted">ستظهر الفئات هنا عند إضافتها للمتجر.</p>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section-space">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">منتجات مميزة</h2>
            <a href="{{ route('products.index') }}" class="section-link">عرض كل المنتجات <i class="fas fa-arrow-left ms-1"></i></a>
    </div>
        <div class="product-grid">
        @foreach($featuredProducts ?? [] as $product)
            @component('components.product-card', ['product' => $product])
            @endcomponent
        @endforeach
        @if(empty($featuredProducts ?? []))
        <div class="col-span-full text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 bg-muted rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-bag text-3xl text-muted-foreground"></i>
            </div>
                <h3 class="text-xl font-semibold mb-2">لا توجد منتجات متاحة</h3>
                <p class="text-muted">ستظهر المنتجات المميزة هنا عند إضافتها للمتجر.</p>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Why Us Section -->
<section class="section-space whyus-section">
    <div class="container">
        <div class="whyus-grid">
            <div class="whyus-card">
                <div class="whyus-icon"><i class="fas fa-shipping-fast"></i></div>
                <div class="whyus-title">شحن سريع</div>
                <div class="whyus-desc">توصيل سريع وآمن إلى باب منزلك</div>
            </div>
            <div class="whyus-card">
                <div class="whyus-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="whyus-title">جودة مضمونة</div>
                <div class="whyus-desc">منتجات مختارة بعناية وبضمان الجودة</div>
            </div>
            <div class="whyus-card">
                <div class="whyus-icon"><i class="fas fa-headset"></i></div>
                <div class="whyus-title">دعم دائم</div>
                <div class="whyus-desc">خدمة عملاء متواصلة لمساعدتك في أي وقت</div>
            </div>
        </div>
    </div>
</section>
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
body { background: var(--main-gray); }
.section-space { padding: 56px 0 0 0; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 16px; }

/* Hero Modern */
.hero-section-modern {
    background: linear-gradient(135deg, var(--main-gold-light) 0%, var(--main-gray) 100%);
    border-radius: 32px;
    box-shadow: 0 8px 40px rgba(169,129,66,0.10);
    margin: 32px 0 56px 0;
    position: relative;
    overflow: hidden;
    min-height: 340px;
    display: flex;
    align-items: center;
}
.hero-bg-pattern {
    position: absolute;
    inset: 0;
    background: url('https://www.transparenttextures.com/patterns/diamond-upholstery.png');
    opacity: 0.08;
    z-index: 1;
}
.hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 32px;
}
.hero-text-box {
    flex: 1 1 340px;
    min-width: 260px;
    padding: 32px 0;
}
.hero-title {
    font-size: 2.7rem;
    font-weight: 900;
    color: var(--main-gold);
    margin-bottom: 18px;
    letter-spacing: -1px;
}
.hero-desc {
    color: #6c757d;
    font-size: 1.2rem;
    margin-bottom: 32px;
    max-width: 500px;
}
.hero-btns { display: flex; gap: 18px; }
.hero-btn-main {
    background: linear-gradient(135deg, var(--main-gold) 0%, var(--main-gold-light) 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 16px 36px;
    font-size: 1.1rem;
    font-weight: 700;
    transition: box-shadow 0.2s, background 0.2s;
    box-shadow: 0 2px 12px rgba(169,129,66,0.08);
}
.hero-btn-main:hover {
    background: linear-gradient(135deg, var(--main-gold-light) 0%, var(--main-gold) 100%);
    color: #fff;
    box-shadow: 0 6px 24px rgba(169,129,66,0.13);
}
.hero-btn-secondary {
    background: var(--main-white);
    color: var(--main-gold);
    border: 2px solid var(--main-gold-light);
    border-radius: 12px;
    padding: 16px 36px;
    font-size: 1.1rem;
    font-weight: 700;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.hero-btn-secondary:hover {
    background: var(--main-gold-light);
    color: #fff;
    border-color: var(--main-gold);
}
.hero-img-box {
    flex: 1 1 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 180px;
}
.hero-img {
    width: 180px;
    height: 180px;
    object-fit: contain;
    border-radius: 50%;
    background: var(--main-white);
    box-shadow: 0 2px 16px rgba(169,129,66,0.10);
    border: 4px solid var(--main-gold-light);
}

/* Section Header */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
}
.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--main-gold);
    margin-bottom: 0;
}
.section-link {
    color: var(--main-gold);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
    font-size: 1.1rem;
}
.section-link:hover {
    color: var(--main-gold-light);
}

/* Category & Product Grid */
.category-grid, .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 28px;
}
.category-card, .product-card {
    background: var(--main-white);
    border-radius: 18px;
    box-shadow: 0 2px 16px rgba(169,129,66,0.07);
    padding: 28px 18px 20px 18px;
    text-align: center;
    transition: box-shadow 0.2s, transform 0.2s;
    border: 1.5px solid var(--main-gold-light);
    position: relative;
}
.category-card:hover, .product-card:hover {
    box-shadow: 0 8px 32px rgba(169,129,66,0.13);
    border-color: var(--main-gold);
    transform: translateY(-4px) scale(1.03);
}
.category-icon {
    width: 60px;
    height: 60px;
    background: var(--main-gold-light);
    color: var(--main-gold);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 16px auto;
    transition: background 0.2s, color 0.2s;
}
.category-card:hover .category-icon {
    background: var(--main-gold);
    color: #fff;
}
.category-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--main-dark);
    margin-bottom: 6px;
}
.category-count {
    color: #a98142cc;
    font-size: 0.97rem;
}
.product-img-box {
    width: 100%;
    aspect-ratio: 1/1.25;
    background: var(--main-gray);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    overflow: hidden;
}
.product-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
    background: #fafafa;
    transition: transform 0.2s;
}
.product-card:hover .product-img {
    transform: scale(1.04);
}
.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--main-dark);
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.product-desc {
    color: #6c757d;
    font-size: 0.97rem;
    margin-bottom: 10px;
    min-height: 36px;
}
.product-price-row {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
    margin-bottom: 12px;
}
.product-old-price {
    color: #888;
    text-decoration: line-through;
    font-size: 1rem;
}
.product-sale-price {
    color: var(--main-gold);
    font-weight: 700;
    font-size: 1.08rem;
}
.product-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: var(--main-gold);
    color: #fff;
    border-radius: 8px;
    padding: 5px 14px;
    font-size: 0.95rem;
    font-weight: 700;
    z-index: 2;
}
.product-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
    justify-content: center;
}
.product-btn {
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
.product-btn.details {
    background: linear-gradient(135deg, var(--main-gold) 0%, var(--main-gold-light) 100%);
    color: #fff;
}
.product-btn.details:hover {
    background: linear-gradient(135deg, var(--main-gold-light) 0%, var(--main-gold) 100%);
}
.product-btn.cart {
    background: var(--main-gray);
    color: var(--main-gold);
    border: 2px solid var(--main-gold);
}
.product-btn.cart:hover {
    background: var(--main-gold);
    color: #fff;
}

/* Why Us */
.whyus-section { margin-bottom: 56px; }
.whyus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 32px;
    justify-content: center;
}
.whyus-card {
    background: var(--main-white);
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(169,129,66,0.07);
    padding: 32px 18px 24px 18px;
    text-align: center;
    border: 1.5px solid var(--main-gold-light);
    transition: box-shadow 0.2s, border 0.2s, transform 0.2s;
}
.whyus-card:hover {
    box-shadow: 0 8px 32px rgba(169,129,66,0.13);
    border-color: var(--main-gold);
    transform: translateY(-4px) scale(1.03);
}
.whyus-icon {
    width: 56px;
    height: 56px;
    background: var(--main-gold-light);
    color: var(--main-gold);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 18px auto;
    transition: background 0.2s, color 0.2s;
}
.whyus-card:hover .whyus-icon {
    background: var(--main-gold);
    color: #fff;
}
.whyus-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--main-dark);
    margin-bottom: 6px;
}
.whyus-desc {
    color: #6c757d;
    font-size: 0.97rem;
}

@media (max-width: 900px) {
    .category-grid, .product-grid, .whyus-grid {
        grid-template-columns: 1fr 1fr;
    }
    .hero-content { flex-direction: column; gap: 0; }
    .hero-img-box { margin-top: 24px; }
}
@media (max-width: 600px) {
    .category-grid, .product-grid, .whyus-grid {
        grid-template-columns: 1fr;
    }
    .hero-section-modern { padding: 18px 0 18px 0; }
    .hero-title { font-size: 2rem; }
    .hero-img { width: 120px; height: 120px; }
}
</style>
@endpush 