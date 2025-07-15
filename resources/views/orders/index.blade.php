@extends('layouts.app')

@section('title', 'Fashion - My Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-shopping-bag ml-2"></i>طلباتي
            </h1>
            <p class="text-gray-600">عرض جميع طلباتك وتتبع حالتها</p>
        </div>
        
        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Order Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">طلب رقم #{{ $order->order_number }}</h2>
                                <p class="text-gray-600 mt-1">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
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
                    </div>
                    
                    <!-- Order Items -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">المنتجات المطلوبة</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($order->orderItems as $item)
                            <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl">
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                                </div>
                                
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">
                                        {{ $item->product_name ?? ($item->product->name ?? 'منتج محذوف') }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        الكمية: {{ $item->quantity }}
                                        @if($item->size) | المقاس: {{ $item->size }} @endif
                                        @if($item->color) | اللون: {{ $item->color }} @endif
                                    </p>
                                    <p class="font-semibold text-gray-800">${{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">المجموع النهائي:</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($order->final_amount, 2) }} د.ل</p>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-eye ml-2"></i>
                                        عرض التفاصيل
                                    </a>
                                    @if($order->status === 'pending')
                                        <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                                              onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                                                <i class="fas fa-times ml-2"></i>
                                                إلغاء الطلب
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
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-bag text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">لا توجد طلبات</h3>
                <p class="text-gray-600 mb-6">لم تقم بإجراء أي طلبات بعد</p>
                <a href="{{ route('home') }}" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-shopping-bag ml-2"></i>
                    ابدأ التسوق
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 