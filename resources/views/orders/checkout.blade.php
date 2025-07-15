@extends('layouts.app')

@section('title', 'Fashion - Checkout')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-credit-card me-2"></i>إتمام الطلب</h1>
    
    <div class="checkout-main">
        <!-- Checkout Form -->
        <div class="checkout-form-area">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        
                        <!-- Shipping Information -->
                        <h6 class="mb-3">معلومات الشحن</h6>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">عنوان الشحن *</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">عنوان الفواتير *</label>
                            <textarea class="form-control @error('billing_address') is-invalid @enderror" 
                                      id="billing_address" name="billing_address" rows="3" required>{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الهاتف *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Payment Method -->
                        <h6 class="mb-3 mt-4">طريقة الدفع</h6>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cash" value="cash" checked>
                                <label class="form-check-label" for="cash">
                                    <i class="fas fa-money-bill me-2"></i>دفع نقدي عند الاستلام
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="credit_card" value="credit_card">
                                <label class="form-check-label" for="credit_card">
                                    <i class="fas fa-credit-card me-2"></i>بطاقة ائتمان
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label" for="bank_transfer">
                                    <i class="fas fa-university me-2"></i>تحويل بنكي
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات إضافية</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="أي ملاحظات أو تعليمات خاصة...">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i>تأكيد الطلب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="checkout-summary">
            <div class="checkout-summary-title">ملخص الطلب</div>
            @foreach($cartItems as $item)
                <div class="product-row">
                    <img src="{{ $item->product->image_url }}"
                         alt="{{ $item->product->name }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                    <div class="product-info">
                        {{ $item->product->name }}
                        <div class="product-meta">
                            الكمية: {{ $item->quantity }}
                            @if($item->size) | مقاس: {{ $item->size }} @endif
                            @if($item->color) | لون: {{ $item->color }} @endif
                        </div>
                    </div>
                    <div class="product-price">
                        {{ $item->subtotal }} د.ل
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-between summary-total">
                <span class="summary-label">الإجمالي:</span>
                <span class="summary-value">{{ $total }} د.ل</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="summary-label">الشحن:</span>
                <span class="summary-value">{{ $shippingCost }} د.ل</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="summary-label">الضريبة (15%):</span>
                <span class="summary-value">{{ $taxAmount }} د.ل</span>
            </div>
            <div class="d-flex justify-content-between summary-total">
                <span class="summary-label">الإجمالي النهائي:</span>
                <span class="summary-value text-primary">{{ $finalAmount }} د.ل</span>
            </div>
            <div class="alert">
                <i class="fas fa-info-circle me-2"></i>
                سيتم التواصل معك لتأكيد الطلب وتحديد موعد التوصيل
            </div>
        </div>
    </div>
</div>

<style>
body {
    background: #f7f8fa !important;
}
.checkout-main {
    display: flex;
    flex-wrap: wrap;
    gap: 32px;
}
.checkout-form-area {
    flex: 2 1 350px;
    min-width: 320px;
}
.checkout-form-area .card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px #0001;
    padding: 22px 18px;
    border: none;
}
.checkout-form-area .card-header {
    background: none;
    border: none;
    padding: 0;
    margin-bottom: 18px;
}
.checkout-form-area .card-header h5 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #795548;
    margin-bottom: 0;
}
.checkout-form-area .card-body {
    padding: 0;
}
.checkout-form-area label {
    font-weight: bold;
    margin-bottom: 4px;
    display: block;
    color: #444;
}
.checkout-form-area input,
.checkout-form-area textarea,
.checkout-form-area select {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 8px 10px;
    margin-bottom: 14px;
    background: #f7f8fa;
    font-size: 1rem;
    color: #222;
}
.checkout-form-area textarea {
    min-height: 60px;
    font-size: 0.97rem;
}
.checkout-form-area .form-check-label {
    font-weight: normal;
    color: #444;
}
.checkout-form-area .form-check-input {
    margin-left: 0.5em;
    accent-color: #b38b59;
}
.checkout-form-area .btn-primary {
    background: #b38b59;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    padding: 10px 0;
    margin-top: 10px;
    transition: background 0.2s;
}
.checkout-form-area .btn-primary:hover {
    background: #a0763c;
}
.checkout-summary {
    flex: 1 1 300px;
    min-width: 260px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px #0001;
    padding: 22px 18px;
    margin-top: 10px;
}
.checkout-summary-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 18px;
    color: #795548;
}
.checkout-summary .product-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
}
.checkout-summary .product-row:last-child {
    border-bottom: none;
}
.checkout-summary .product-row img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eee;
    background: #fafafa;
}
.checkout-summary .product-info {
    flex: 1;
    font-size: 1rem;
    color: #222;
}
.checkout-summary .product-meta {
    color: #888;
    font-size: 0.93em;
    margin-top: 2px;
}
.checkout-summary .product-price {
    font-weight: bold;
    color: #795548;
    min-width: 60px;
    text-align: left;
    font-size: 1.05rem;
}
.checkout-summary .summary-total {
    font-size: 1.1rem;
    font-weight: bold;
    color: #222;
    margin-top: 10px;
}
.checkout-summary .summary-label {
    color: #888;
    font-size: 0.98rem;
}
.checkout-summary .summary-value {
    font-weight: bold;
    color: #222;
}
.checkout-summary .alert {
    background: #f7f8fa;
    border: none;
    color: #795548;
    font-size: 0.97rem;
    margin-top: 18px;
}
@media (max-width: 900px) {
    .checkout-main {
        flex-direction: column;
        gap: 0;
    }
    .checkout-form-area .card {
        margin-bottom: 24px;
    }
    .checkout-summary {
        margin-top: 24px;
    }
}
.checkout-form-area .form-check {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
}
.checkout-form-area .form-check-input[type="radio"] {
    margin-left: 0;
    margin-right: 0;
    position: relative;
    top: 0;
    accent-color: #b38b59;
    width: 18px;
    height: 18px;
}
.checkout-form-area .form-check-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.98rem;
}
</style>
@endsection 