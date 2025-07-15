@extends('layouts.app')

@section('title', 'Fashion - Manage Orders')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إدارة الطلبات</h1>
                    <p class="text-gray-600 mt-1">عرض وإدارة جميع طلبات العملاء</p>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-right ml-2"></i>العودة للوحة التحكم
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

        <!-- Filters and Search -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="البحث برقم الطلب أو اسم العميل..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة الطلب</label>
                    <select id="status" name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                    <input type="date" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search ml-2"></i>تطبيق الفلتر
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">في الانتظار</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">مكتملة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $deliveredOrders }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">ملغية</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $cancelledOrders }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الطلب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-blue-600"></i>
                                        </div>
                                        <div class="mr-3">
                                            <p class="text-sm font-bold text-gray-900">#{{ $order->order_number ?? $order->id }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->orderItems->count() }} منتج</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                                        @if($order->user->phone)
                                            <p class="text-xs text-gray-500">{{ $order->user->phone }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($order->final_amount, 2) }} د.ل</p>
                                    @if($order->discount_amount > 0)
                                        <p class="text-xs text-green-600">خصم: {{ number_format($order->discount_amount, 2) }} د.ل</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($order->status === 'pending') 
                                            <i class="fas fa-clock ml-1"></i>في الانتظار
                                        @elseif($order->status === 'processing') 
                                            <i class="fas fa-cog ml-1"></i>قيد المعالجة
                                        @elseif($order->status === 'shipped') 
                                            <i class="fas fa-shipping-fast ml-1"></i>تم الشحن
                                        @elseif($order->status === 'delivered') 
                                            <i class="fas fa-check ml-1"></i>تم التوصيل
                                        @elseif($order->status === 'cancelled') 
                                            <i class="fas fa-times ml-1"></i>ملغي
                                        @else 
                                            {{ $order->status }} 
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <p>{{ $order->created_at->format('Y-m-d') }}</p>
                                    <p class="text-xs">{{ $order->created_at->format('H:i') }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>
                                        <div class="relative">
                                            <button type="button" 
                                                    onclick="toggleStatusMenu({{ $order->id }})"
                                                    class="text-gray-600 hover:text-gray-900 transition-colors">
                                                <i class="fas fa-ellipsis-v text-lg"></i>
                                            </button>
                                            <div id="status-menu-{{ $order->id }}" 
                                                 class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-10">
                                                <div class="py-1">
                                                    <button onclick="updateStatus({{ $order->id }}, 'pending')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'pending' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                        <i class="fas fa-clock ml-2"></i>في الانتظار
                                                    </button>
                                                    <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                        <i class="fas fa-cog ml-2"></i>قيد المعالجة
                                                    </button>
                                                    <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'shipped' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                        <i class="fas fa-shipping-fast ml-2"></i>تم الشحن
                                                    </button>
                                                    <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $order->status === 'delivered' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                        <i class="fas fa-check ml-2"></i>تم التوصيل
                                                    </button>
                                                    <hr class="my-1">
                                                    <button onclick="updateStatus({{ $order->id }}, 'cancelled')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 {{ $order->status === 'cancelled' ? 'bg-red-50' : '' }}">
                                                        <i class="fas fa-times ml-2"></i>إلغاء الطلب
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد طلبات</h3>
                    <p class="text-gray-600">لم يتم العثور على أي طلبات تطابق معايير البحث</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleStatusMenu(orderId) {
    const menu = document.getElementById(`status-menu-${orderId}`);
    const allMenus = document.querySelectorAll('[id^="status-menu-"]');
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m.id !== `status-menu-${orderId}`) {
            m.classList.add('hidden');
        }
    });
    
    // Toggle current menu
    menu.classList.toggle('hidden');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    const menus = document.querySelectorAll('[id^="status-menu-"]');
    menus.forEach(menu => {
        if (!menu.contains(event.target) && !event.target.closest('button')) {
            menu.classList.add('hidden');
        }
    });
});

function updateStatus(orderId, status) {
    const statusNames = {
        'pending': 'في الانتظار',
        'processing': 'قيد المعالجة',
        'shipped': 'تم الشحن',
        'delivered': 'تم التوصيل',
        'cancelled': 'ملغي'
    };
    
    if (confirm(`هل أنت متأكد من تحديث حالة الطلب إلى "${statusNames[status]}"؟`)) {
        fetch(`/admin/orders/${orderId}/status`, {
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