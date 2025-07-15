@extends('layouts.app')

@section('title', 'Fashion - ' . $product->name)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Product Images -->
    <div class="col-md-6 mb-4">
        <div class="card">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
            @else
                <div class="card-img-top bg-gray-100 d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
            @endif
        </div>
        
        @if($product->images && count($product->images) > 0)
        <div class="row mt-3">
            @foreach($product->images as $image)
            <div class="col-3">
                <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" class="img-thumbnail" alt="{{ $product->name }}">
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
    <!-- Product Details -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">{{ $product->name }}</h1>
                
                <!-- Price -->
                <div class="mb-3">
                    @if($product->is_on_sale)
                        <span class="text-decoration-line-through text-muted fs-5">{{ $product->price }} د.ل</span>
                        <span class="text-danger fw-bold fs-4 ms-2">{{ $product->sale_price }} د.ل</span>
                        <span class="badge bg-danger ms-2">خصم {{ $product->discount_percentage }}%</span>
                    @else
                        <span class="fw-bold fs-4">{{ $product->price }} د.ل</span>
                    @endif
                </div>
                
                <!-- Stock Status -->
                <div class="mb-3">
                    @if($product->stock > 0)
                        <span class="badge bg-success">متوفر في المخزون ({{ $product->stock }} قطعة)</span>
                    @else
                        <span class="badge bg-secondary">نفذ من المخزون</span>
                    @endif
                </div>
                
                <!-- Description -->
                <div class="mb-4">
                    <h5>الوصف</h5>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                
                <!-- Add to Cart Form -->
                @if($product->stock > 0)
                <form action="{{ route('cart.add.product', $product) }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="row">
                        @if($product->sizes && count($product->sizes) > 0)
                        <div class="col-md-6 mb-3">
                            <label for="size" class="form-label">المقاس</label>
                            <select class="form-select" name="size" id="size">
                                <option value="">اختر المقاس</option>
                                @foreach($product->sizes as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        @if($product->colors && count($product->colors) > 0)
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">اللون</label>
                            <select class="form-select" name="color" id="color">
                                <option value="">اختر اللون</option>
                                @foreach($product->colors as $color)
                                    <option value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">الكمية</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" 
                                   value="1" min="1" max="{{ $product->stock }}">
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cart-plus me-2"></i>إضافة إلى السلة
                        </button>
                    </div>
                </form>
                @endif
                
                <!-- Product Info -->
                <div class="border-top pt-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">الفئة:</small><br>
                            <strong>{{ $product->category->name }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">المشاهدات:</small><br>
                            <strong>{{ $product->views }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="mt-5">
    <h3 class="mb-4">منتجات مشابهة</h3>
    <div class="row">
        @foreach($relatedProducts as $relatedProduct)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    @if($relatedProduct->image_url)
                        <img src="{{ $relatedProduct->image_url }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                    @else
                        <div class="card-img-top bg-gray-100 d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image text-2xl text-gray-400"></i>
                        </div>
                    @endif
                    @if($relatedProduct->is_on_sale)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                            -{{ $relatedProduct->discount_percentage }}%
                        </span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            @if($relatedProduct->is_on_sale)
                                <span class="text-decoration-line-through text-muted small">{{ $relatedProduct->price }} د.ل</span>
                                <span class="text-danger fw-bold">{{ $relatedProduct->sale_price }} د.ل</span>
                            @else
                                <span class="fw-bold">{{ $relatedProduct->price }} د.ل</span>
                            @endif
                        </div>
                        <a href="{{ route('product', $relatedProduct->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection 