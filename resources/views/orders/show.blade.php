@extends('layouts.app')

@section('title', 'Fashion - Order Details - ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">تفاصيل الطلب</h1>
                    <p class="text-gray-600 mt-2">رقم الطلب: {{ $order->order_number }}</p>
                </div>
                <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للطلبات
                </a>
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">حالة الطلب</h2>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($order->status === 'pending') في الانتظار
                            @elseif($order->status === 'processing') قيد المعالجة
                            @elseif($order->status === 'shipped') تم الشحن
                            @elseif($order->status === 'delivered') تم التوصيل
                            @elseif($order->status === 'cancelled') ملغي
                            @else {{ $order->status }} @endif
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($order->payment_status === 'pending') دفع معلق
                            @elseif($order->payment_status === 'paid') مدفوع
                            @elseif($order->payment_status === 'failed') فشل في الدفع
                            @else {{ $order->payment_status }} @endif
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">تاريخ الطلب</p>
                    <p class="font-semibold">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">المنتجات المطلوبة</h2>
            
            @if($order->orderItems && $order->orderItems->count() > 0)
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">
                                {{ $item->product->name ?? 'منتج محذوف' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                الكمية: {{ $item->quantity }}
                                @if($item->size)
                                    | المقاس: {{ $item->size }}
                                @endif
                                @if($item->color)
                                    | اللون: {{ $item->color }}
                                @endif
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">{{ number_format($item->price, 2) }} د.ل</p>
                            <p class="text-sm text-gray-600">المجموع: {{ number_format($item->price * $item->quantity, 2) }} د.ل</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-box text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">لا توجد منتجات في هذا الطلب</p>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">ملخص الطلب</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">المجموع الفرعي:</span>
                    <span class="font-semibold">{{ number_format($order->total_amount, 2) }} د.ل</span>
                </div>
                
                @if($order->shipping_cost > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">تكلفة الشحن:</span>
                    <span class="font-semibold">{{ number_format($order->shipping_cost, 2) }} د.ل</span>
                </div>
                @endif
                
                @if($order->tax_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">الضريبة:</span>
                    <span class="font-semibold">{{ number_format($order->tax_amount, 2) }} د.ل</span>
                </div>
                @endif
                
                @if($order->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">الخصم:</span>
                    <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 2) }} د.ل</span>
                </div>
                @endif
                
                <div class="border-t pt-3">
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-800">المجموع النهائي:</span>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($order->final_amount, 2) }} د.ل</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">معلومات الشحن</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">عنوان الشحن</h3>
                    <p class="text-gray-600">{{ $order->shipping_address }}</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">عنوان الفواتير</h3>
                    <p class="text-gray-600">{{ $order->billing_address }}</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">رقم الهاتف</h3>
                    <p class="text-gray-600">{{ $order->phone }}</p>
                </div>
                
                @if($order->notes)
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">ملاحظات</h3>
                    <p class="text-gray-600">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">جدول زمني للطلب</h2>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">تم إنشاء الطلب</p>
                        <p class="text-sm text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                
                @if($order->status !== 'pending')
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cog text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">قيد المعالجة</p>
                        <p class="text-sm text-gray-600">{{ $order->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->shipped_at)
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shipping-fast text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">تم الشحن</p>
                        <p class="text-sm text-gray-600">{{ $order->shipped_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif
                
                @if($order->delivered_at)
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-home text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">تم التوصيل</p>
                        <p class="text-sm text-gray-600">{{ $order->delivered_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($order->status === 'pending')
        <div class="mt-6 flex gap-4">
            <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                  onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                    <i class="fas fa-times ml-2"></i>
                    إلغاء الطلب
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection 