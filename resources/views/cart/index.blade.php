@extends('layouts.app')

@section('title', 'Fashion - Shopping Cart')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>سلة التسوق</h1>
    
    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-container">
                    <div class="cart-header">
                        <h5 class="mb-0">المنتجات في السلة ({{ $cartItems->count() }})</h5>
                    </div>
                    <div class="cart-body">
                        <div class="cart-main-box">
                            @foreach($cartItems as $item)
                            <div class="cart-product-row">
                                <div class="cart-product-image">
                                    <img src="{{ $item->product->image_url }}"
                                         alt="{{ $item->product->name }}"
                                         class="cart-product-img"
                                         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                                </div>
                                <div class="cart-product-details">
                                    <div class="cart-product-name">{{ $item->product->name }}</div>
                                    <div class="cart-product-desc">{{ Str::limit($item->product->description, 40) }}</div>
                                    <div class="cart-product-meta">
                                        @if($item->size)
                                            <span class="meta-tag">المقاس: {{ $item->size }}</span>
                                        @endif
                                        @if($item->color)
                                            <span class="meta-tag">اللون: {{ $item->color }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="cart-product-qty">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity-controls">
                                            <input type="number" class="quantity-input" name="quantity" 
                                                   value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                                            <button type="submit" title="تحديث" class="update-btn">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="cart-product-price">
                                    <span class="price-amount">{{ $item->subtotal }}</span>
                                    <span class="price-currency">د.ل</span>
                                </div>
                                <div class="cart-product-actions">
                                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="حذف" class="delete-btn" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="cart-actions">
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="clear-cart-btn" 
                                        onclick="return confirm('هل أنت متأكد من تفريغ السلة؟')">
                                    <i class="fas fa-trash me-2"></i>تفريغ السلة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <div class="summary-header">
                        <h5 class="mb-0">ملخص الطلب</h5>
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span>إجمالي المنتجات:</span>
                            <span>{{ $total }} د.ل</span>
                        </div>
                        <div class="summary-row">
                            <span>الشحن:</span>
                            <span>50 د.ل</span>
                        </div>
                        <div class="summary-row">
                            <span>الضريبة (15%):</span>
                            <span>{{ $total * 0.15 }} د.ل</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-total">
                            <span>الإجمالي النهائي:</span>
                            <span class="total-amount">{{ $total + 50 + ($total * 0.15) }} د.ل</span>
                        </div>
                        
                        <div class="summary-actions">
                            <a href="{{ route('checkout') }}" class="checkout-btn">
                                <i class="fas fa-credit-card me-2"></i>إتمام الطلب
                            </a>
                            <a href="{{ route('home') }}" class="continue-shopping-btn">
                                <i class="fas fa-shopping-bag me-2"></i>مواصلة التسوق
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <i class="fas fa-shopping-cart empty-cart-icon"></i>
            <h3>السلة فارغة</h3>
            <p>لم تقم بإضافة أي منتجات إلى السلة بعد</p>
            <a href="{{ route('home') }}" class="start-shopping-btn">
                <i class="fas fa-shopping-bag me-2"></i>ابدأ التسوق
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Cart Container */
.cart-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 24px;
}

.cart-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;
}

.cart-body {
    padding: 0;
}

/* Cart Main Box */
.cart-main-box {
    padding: 0;
    margin: 0;
    background: transparent;
    box-shadow: none;
}

/* Cart Product Row */
.cart-product-row {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 24px;
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s ease;
}

.cart-product-row:hover {
    background-color: #f8f9fa;
}

.cart-product-row:last-child {
    border-bottom: none;
}

/* Product Image */
.cart-product-image {
    flex-shrink: 0;
}

.cart-product-img {
    width: 80px !important;
    height: 80px !important;
    object-fit: cover !important;
    border-radius: 12px !important;
    border: 2px solid #f1f3f4 !important;
    background: #fafafa !important;
    display: block !important;
    transition: transform 0.2s ease;
}

.cart-product-img:hover {
    transform: scale(1.05);
}

/* Product Details */
.cart-product-details {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.cart-product-name {
    font-weight: 600;
    font-size: 1.1rem;
    color: #2c3e50;
    margin: 0;
    line-height: 1.3;
}

.cart-product-desc {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.4;
}

.cart-product-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.meta-tag {
    background: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Quantity Controls */
.cart-product-qty {
    flex-shrink: 0;
}

.quantity-form {
    margin: 0;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 4px;
}

.quantity-input {
    width: 50px !important;
    border: none !important;
    background: white !important;
    text-align: center !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    border-radius: 6px !important;
    padding: 6px !important;
}

.quantity-input:focus {
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2) !important;
}

.update-btn {
    background: #667eea !important;
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 6px 8px !important;
    font-size: 0.8rem !important;
    transition: background-color 0.2s ease !important;
}

.update-btn:hover {
    background: #5a6fd8 !important;
}

/* Price */
.cart-product-price {
    flex-shrink: 0;
    text-align: center;
    min-width: 80px;
}

.price-amount {
    font-weight: 700;
    font-size: 1.1rem;
    color: #2c3e50;
}

.price-currency {
    font-size: 0.9rem;
    color: #6c757d;
    margin-right: 2px;
}

/* Actions */
.cart-product-actions {
    flex-shrink: 0;
}

.delete-btn {
    background: #dc3545 !important;
    color: white !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    font-size: 0.9rem !important;
    transition: all 0.2s ease !important;
}

.delete-btn:hover {
    background: #c82333 !important;
    transform: scale(1.05);
}

/* Cart Actions */
.cart-actions {
    padding: 20px 24px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    text-align: right;
}

.clear-cart-btn {
    background: #6c757d !important;
    color: white !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 16px !important;
    font-size: 0.9rem !important;
    transition: background-color 0.2s ease !important;
}

.clear-cart-btn:hover {
    background: #5a6268 !important;
}

/* Order Summary */
.order-summary {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: sticky;
    top: 20px;
}

.summary-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;
}

.summary-body {
    padding: 24px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.95rem;
    color: #6c757d;
}

.summary-divider {
    height: 1px;
    background: #e9ecef;
    margin: 16px 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.total-amount {
    color: #28a745;
    font-size: 1.2rem;
}

.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.checkout-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 16px 24px !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    text-align: center !important;
    transition: all 0.3s ease !important;
    display: block !important;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    color: white !important;
}

.continue-shopping-btn {
    background: transparent !important;
    color: #6c757d !important;
    border: 2px solid #e9ecef !important;
    border-radius: 12px !important;
    padding: 14px 24px !important;
    font-size: 0.95rem !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    text-align: center !important;
    transition: all 0.3s ease !important;
    display: block !important;
}

.continue-shopping-btn:hover {
    background: #f8f9fa !important;
    border-color: #6c757d !important;
    color: #495057 !important;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-cart-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 24px;
    opacity: 0.6;
}

.empty-cart h3 {
    color: #2c3e50;
    margin-bottom: 12px;
    font-weight: 600;
}

.empty-cart p {
    color: #6c757d;
    margin-bottom: 32px;
    font-size: 1.1rem;
}

.start-shopping-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 16px 32px !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    display: inline-block !important;
}

.start-shopping-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-product-row {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
        padding: 16px 20px;
    }
    
    .cart-product-image {
        align-self: center;
    }
    
    .cart-product-details {
        text-align: center;
    }
    
    .cart-product-meta {
        justify-content: center;
    }
    
    .cart-product-qty {
        align-self: center;
    }
    
    .cart-product-price {
        align-self: center;
    }
    
    .cart-product-actions {
        align-self: center;
    }
    
    .order-summary {
        margin-top: 24px;
        position: static;
    }
}

@media (max-width: 576px) {
    .cart-header,
    .summary-header {
        padding: 16px 20px;
    }
    
    .cart-body {
        padding: 0;
    }
    
    .summary-body {
        padding: 20px;
    }
    
    .cart-actions {
        padding: 16px 20px;
    }
}
</style>
@endpush 