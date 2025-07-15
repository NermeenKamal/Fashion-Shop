@extends('layouts.app')

@section('title', 'Fashion - Order Details')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700 mr-4">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">تفاصيل الطلب</h1>
                            <p class="text-gray-600 mt-1">طلب #{{ $order->order_number ?? $order->id }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-right ml-2"></i>العودة للطلبات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 ml-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">حالة الطلب</h3>
                        <div class="relative">
                            <button type="button" 
                                    onclick="toggleStatusMenu()"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit ml-2"></i>تحديث الحالة
                            </button>
                            <div id="status-menu" 
                                 class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-10">
                                <div class="py-1">
                                    <button onclick="updateStatus('pending')" 
                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'pending' ? 'bg-blue-50 text-blue-700' : '' }}">
                                        <i class="fas fa-clock ml-2"></i>في الانتظار
                                    </button>
                                    <button onclick="updateStatus('processing')" 
                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-700' : '' }}">
                                        <i class="fas fa-cog ml-2"></i>قيد المعالجة
                                    </button>
                                    <button onclick="updateStatus('shipped')" 
                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'shipped' ? 'bg-blue-50 text-blue-700' : '' }}">
                                        <i class="fas fa-shipping-fast ml-2"></i>تم الشحن
                                    </button>
                                    <button onclick="updateStatus('delivered')" 
                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'delivered' ? 'bg-blue-50 text-blue-700' : '' }}">
                                        <i class="fas fa-check ml-2"></i>تم التوصيل
                                    </button>
                                    <hr class="my-1">
                                    <button onclick="updateStatus('cancelled')" 
                                            class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 {{ $order->status === 'cancelled' ? 'bg-red-50' : '' }}">
                                        <i class="fas fa-times ml-2"></i>إلغاء الطلب
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($order->status === 'pending') 
                                <i class="fas fa-clock ml-2"></i>في الانتظار
                            @elseif($order->status === 'processing') 
                                <i class="fas fa-cog ml-2"></i>قيد المعالجة
                            @elseif($order->status === 'shipped') 
                                <i class="fas fa-shipping-fast ml-2"></i>تم الشحن
                            @elseif($order->status === 'delivered') 
                                <i class="fas fa-check ml-2"></i>تم التوصيل
                            @elseif($order->status === 'cancelled') 
                                <i class="fas fa-times ml-2"></i>ملغي
                            @else 
                                {{ $order->status }} 
                            @endif
                        </span>
                        
                        <div class="mr-4 text-sm text-gray-600">
                            <p>تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                            @if($order->updated_at != $order->created_at)
                                <p>آخر تحديث: {{ $order->updated_at->format('Y-m-d H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">المنتجات المطلوبة</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center p-4 border border-gray-200 rounded-xl">
                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="{{ $item->product->image_url }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                            </div>
                            <div class="mr-4 flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $item->product->name ?? 'منتج محذوف' }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->product->description ?? '' }}</p>
                                <div class="flex items-center mt-2 space-x-4 space-x-reverse">
                                    <span class="text-sm text-gray-500">الكمية: {{ $item->quantity }}</span>
                                    @if($item->size)
                                        <span class="text-sm text-gray-500">المقاس: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                        <span class="text-sm text-gray-500">اللون: {{ $item->color }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="font-bold text-gray-900">{{ number_format($item->price, 2) }} د.ل</p>
                                <p class="text-sm text-gray-500">المجموع: {{ number_format($item->price * $item->quantity, 2) }} د.ل</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">سجل الطلب</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div class="mr-4">
                                <p class="font-medium text-gray-900">تم إنشاء الطلب</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->status !== 'pending')
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-cog text-blue-600 text-sm"></i>
                            </div>
                            <div class="mr-4">
                                <p class="font-medium text-gray-900">تم معالجة الطلب</p>
                                <p class="text-sm text-gray-500">{{ $order->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'shipped' || $order->status === 'delivered')
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-shipping-fast text-purple-600 text-sm"></i>
                            </div>
                            <div class="mr-4">
                                <p class="font-medium text-gray-900">تم شحن الطلب</p>
                                <p class="text-sm text-gray-500">{{ $order->shipped_at ? $order->shipped_at->format('Y-m-d H:i') : 'قريباً' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'delivered')
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div class="mr-4">
                                <p class="font-medium text-gray-900">تم توصيل الطلب</p>
                                <p class="text-sm text-gray-500">{{ $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i') : 'قريباً' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'cancelled')
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-times text-red-600 text-sm"></i>
                            </div>
                            <div class="mr-4">
                                <p class="font-medium text-gray-900">تم إلغاء الطلب</p>
                                <p class="text-sm text-gray-500">{{ $order->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">معلومات العميل</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="mr-3">
                                <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                            </div>
                        </div>
                        
                        @if($order->user->phone)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone ml-2"></i>
                            <span>{{ $order->user->phone }}</span>
                        </div>
                        @endif
                        
                        @if($order->user->address)
                        <div class="flex items-start text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt ml-2 mt-1"></i>
                            <span>{{ $order->user->address }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar ml-2"></i>
                            <span>عضو منذ {{ $order->user->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">ملخص الطلب</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">المجموع الفرعي:</span>
                            <span class="font-medium">{{ number_format($order->subtotal, 2) }} د.ل</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">الخصم:</span>
                            <span class="font-medium text-green-600">-{{ number_format($order->discount_amount, 2) }} د.ل</span>
                        </div>
                        @endif
                        
                        @if($order->shipping_cost > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">الشحن:</span>
                            <span class="font-medium">{{ number_format($order->shipping_cost, 2) }} د.ل</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">الضريبة:</span>
                            <span class="font-medium">{{ number_format($order->tax_amount, 2) }} د.ل</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="flex justify-between text-lg font-bold">
                            <span>المجموع النهائي:</span>
                            <span>{{ number_format($order->final_amount, 2) }} د.ل</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">معلومات الدفع</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">طريقة الدفع:</span>
                            <span class="font-medium">{{ $order->payment_method ?? 'غير محدد' }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">حالة الدفع:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($order->payment_status === 'paid') 
                                    <i class="fas fa-check ml-1"></i>مدفوع
                                @elseif($order->payment_status === 'pending') 
                                    <i class="fas fa-clock ml-1"></i>في الانتظار
                                @else 
                                    <i class="fas fa-times ml-1"></i>غير مدفوع
                                @endif
                            </span>
                        </div>
                        
                        @if($order->payment_id)
                        <div class="flex justify-between">
                            <span class="text-gray-600">رقم المعاملة:</span>
                            <span class="font-medium">{{ $order->payment_id }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatusMenu() {
    const menu = document.getElementById('status-menu');
    menu.classList.toggle('hidden');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('status-menu');
    if (!menu.contains(event.target) && !event.target.closest('button')) {
        menu.classList.add('hidden');
    }
});

function updateStatus(status) {
    const statusNames = {
        'pending': 'في الانتظار',
        'processing': 'قيد المعالجة',
        'shipped': 'تم الشحن',
        'delivered': 'تم التوصيل',
        'cancelled': 'ملغي'
    };
    
    if (confirm(`هل أنت متأكد من تحديث حالة الطلب إلى "${statusNames[status]}"؟`)) {
        fetch(`/admin/orders/{{ $order->id }}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تحديث حالة الطلب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحديث حالة الطلب');
        });
    }
}
</script>
@endsection 