@extends('layouts.app')

@section('title', 'Fashion - ' . $category->name)

@section('content')
<!-- Category Header -->
<div class="category-header bg-light py-4 mb-4 rounded">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        
        <div class="text-center mt-3">
            <h1 class="display-5">{{ $category->name }}</h1>
            <p class="lead text-muted">{{ $category->description }}</p>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="container">
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/300x400/{{ $loop->index % 2 == 0 ? 'f8f9fa' : 'e9ecef' }}/6c757d?text={{ urlencode($product->name) }}" 
                             class="card-img-top" alt="{{ $product->name }}">
                        @if($product->is_on_sale)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                        @if($product->stock < 10 && $product->stock > 0)
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                آخر {{ $product->stock }} قطع
                            </span>
                        @endif
                        @if($product->stock == 0)
                            <span class="badge bg-secondary position-absolute top-0 end-0 m-2">
                                نفذ من المخزون
                            </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                @if($product->is_on_sale)
                                    <span class="text-decoration-line-through text-muted">{{ $product->price }} د.ل</span>
                                    <span class="text-danger fw-bold">{{ $product->sale_price }} د.ل</span>
                                @else
                                    <span class="fw-bold">{{ $product->price }} د.ل</span>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('product', $product->slug) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                </a>
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add.product', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-outline-success w-100">
                                            <i class="fas fa-cart-plus me-2"></i>إضافة للسلة
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h3>لا توجد منتجات في هذه الفئة</h3>
            <p class="text-muted">جاري إضافة منتجات جديدة قريباً</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>العودة للرئيسية
            </a>
        </div>
    @endif
</div>
@endsection 