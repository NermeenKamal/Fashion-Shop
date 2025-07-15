@extends('layouts.app')

@section('title', 'Fashion - Account Activity')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-right text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">نشاط الحساب</h1>
            </div>
            <p class="text-gray-600">عرض سجل طلباتك ونشاطك في الموقع</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Activity Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">ملخص النشاط</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-bag text-blue-600 ml-3"></i>
                                <span class="font-medium text-gray-900">إجمالي الطلبات</span>
                            </div>
                            <span class="text-2xl font-bold text-blue-600">{{ $orders->total() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 ml-3"></i>
                                <span class="font-medium text-gray-900">الطلبات المكتملة</span>
                            </div>
                            <span class="text-2xl font-bold text-green-600">{{ $orders->where('status', 'delivered')->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-yellow-600 ml-3"></i>
                                <span class="font-medium text-gray-900">في الانتظار</span>
                            </div>
                            <span class="text-2xl font-bold text-yellow-600">{{ $orders->where('status', 'pending')->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-shipping-fast text-purple-600 ml-3"></i>
                                <span class="font-medium text-gray-900">قيد الشحن</span>
                            </div>
                            <span class="text-2xl font-bold text-purple-600">{{ $orders->where('status', 'shipped')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">سجل الطلبات</h3>
                        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            عرض الكل
                        </a>
                    </div>
                    
                    @if($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($orders as $order)
                            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-blue-600"></i>
                                        </div>
                                        <div class="mr-4">
                                            <h4 class="font-semibold text-gray-900">طلب #{{ $order->order_number }}</h4>
                                            <p class="text-sm text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-gray-900 text-lg">{{ number_format($order->final_amount, 2) }} د.ل</p>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
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
                                    </div>
                                </div>
                                
                                <!-- Order Items -->
                                <div class="border-t border-gray-100 pt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($order->orderItems->take(3) as $item)
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <img src="{{ $item->product->image_url }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-12 h-12 rounded-lg object-cover"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-600">الكمية: {{ $item->quantity }}</p>
                                            </div>
                                            <p class="font-semibold text-gray-900">${{ number_format($item->price, 2) }}</p>
                                        </div>
                                        @endforeach
                                        
                                        @if($order->orderItems->count() > 3)
                                            <div class="col-span-full text-center">
                                                <p class="text-sm text-gray-600">و {{ $order->orderItems->count() - 3 }} منتجات أخرى</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Order Actions -->
                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            <i class="fas fa-eye ml-1"></i>عرض التفاصيل
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium" 
                                                        onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                                                    <i class="fas fa-times ml-1"></i>إلغاء الطلب
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="mt-8">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد طلبات بعد</h3>
                            <p class="text-gray-600 mb-6">ابدأ التسوق الآن لإنشاء أول طلب لك</p>
                            <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                <i class="fas fa-shopping-bag ml-2"></i>ابدأ التسوق
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 