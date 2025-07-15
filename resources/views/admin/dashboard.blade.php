@extends('layouts.app')

@section('title', 'Fashion - Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">لوحة التحكم</h1>
                    <p class="text-gray-600 mt-1">مرحباً بك في لوحة تحكم متجر الملابس</p>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="relative">
                        <select id="period" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>هذا الشهر</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>هذا العام</option>
                        </select>
                    </div>
                    <a href="{{ route('admin.analytics') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-line ml-2"></i>التحليلات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المبيعات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($sales_stats['total_sales'], 2) }} د.ل</p>
                    </div>
                </div>
                <div class="mt-4">
                    @if(isset($sales_stats['growth_rate']))
                        <span class="text-{{ $sales_stats['growth_rate'] >= 0 ? 'green' : 'red' }}-600 text-sm font-medium">
                            <i class="fas fa-arrow-{{ $sales_stats['growth_rate'] >= 0 ? 'up' : 'down' }} ml-1"></i>
                            {{ $sales_stats['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($sales_stats['growth_rate'], 1) }}%
                        </span>
                        <span class="text-gray-500 text-sm">مقارنة بالشهر السابق</span>
                    @else
                        <span class="text-gray-500 text-sm">لا توجد بيانات سابقة</span>
                    @endif
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $sales_stats['total_orders'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    @if(isset($sales_stats['orders_growth']))
                        <span class="text-{{ $sales_stats['orders_growth'] >= 0 ? 'blue' : 'red' }}-600 text-sm font-medium">
                            <i class="fas fa-arrow-{{ $sales_stats['orders_growth'] >= 0 ? 'up' : 'down' }} ml-1"></i>
                            {{ $sales_stats['orders_growth'] >= 0 ? '+' : '' }}{{ number_format($sales_stats['orders_growth'], 1) }}%
                        </span>
                        <span class="text-gray-500 text-sm">مقارنة بالشهر السابق</span>
                    @else
                        <span class="text-gray-500 text-sm">لا توجد بيانات سابقة</span>
                    @endif
                </div>
            </div>

            <!-- Average Order -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-bar text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">متوسط الطلب</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($sales_stats['average_order'], 2) }} د.ل</p>
                    </div>
                </div>
                <div class="mt-4">
                    @if(isset($sales_stats['avg_order_growth']))
                        <span class="text-{{ $sales_stats['avg_order_growth'] >= 0 ? 'purple' : 'red' }}-600 text-sm font-medium">
                            <i class="fas fa-arrow-{{ $sales_stats['avg_order_growth'] >= 0 ? 'up' : 'down' }} ml-1"></i>
                            {{ $sales_stats['avg_order_growth'] >= 0 ? '+' : '' }}{{ number_format($sales_stats['avg_order_growth'], 1) }}%
                        </span>
                        <span class="text-gray-500 text-sm">مقارنة بالشهر السابق</span>
                    @else
                        <span class="text-gray-500 text-sm">لا توجد بيانات سابقة</span>
                    @endif
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المستخدمين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $user_stats['total_users'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    @if(isset($user_stats['new_users']))
                        <span class="text-orange-600 text-sm font-medium">
                            <i class="fas fa-user-plus ml-1"></i>
                            +{{ $user_stats['new_users'] }} جديد
                        </span>
                        <span class="text-gray-500 text-sm">هذا الشهر</span>
                    @else
                        <span class="text-gray-500 text-sm">لا توجد بيانات سابقة</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts and Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Order Status Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">توزيع حالة الطلبات</h3>
                @if(count($order_status_distribution) > 0)
                    <div class="space-y-4">
                        @foreach($order_status_distribution as $status => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full 
                                    @if($status === 'pending') bg-yellow-400
                                    @elseif($status === 'processing') bg-blue-400
                                    @elseif($status === 'shipped') bg-purple-400
                                    @elseif($status === 'delivered') bg-green-400
                                    @elseif($status === 'cancelled') bg-red-400
                                    @else bg-gray-400 @endif">
                                </div>
                                <span class="mr-3 text-sm font-medium text-gray-700">
                                    @if($status === 'pending') في الانتظار
                                    @elseif($status === 'processing') قيد المعالجة
                                    @elseif($status === 'shipped') تم الشحن
                                    @elseif($status === 'delivered') تم التوصيل
                                    @elseif($status === 'cancelled') ملغي
                                    @else {{ $status }} @endif
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-gray-400 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-600">لا توجد طلبات بعد</p>
                    </div>
                @endif
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">أفضل المنتجات مبيعاً</h3>
                @if(count($top_products) > 0)
                    <div class="space-y-4">
                        @foreach($top_products as $product)
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="{{ $product->product->image_url }}" 
                                     alt="{{ $product->product->name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                            </div>
                            <div class="mr-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $product->product_name ?? ($product->product->name ?? 'منتج محذوف') }}
                                </p>
                                <p class="text-xs text-gray-500">تم بيع {{ $product->total_sold }} قطعة</p>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($product->total_revenue, 2) }} د.ل</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-box text-gray-400 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-600">لا توجد مبيعات بعد</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Orders and Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Orders -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">الطلبات الحديثة</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        عرض الكل
                    </a>
                </div>
                @if(count($recent_orders) > 0)
                    <div class="space-y-4">
                        @foreach($recent_orders as $order)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-gray-600"></i>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-900">طلب #{{ $order->order_number ?? $order->id }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->user->name }}</p>
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($order->final_amount, 2) }} د.ل</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-bag text-gray-400 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-600">لا توجد طلبات حديثة</p>
                    </div>
                @endif
            </div>

            <!-- Alerts -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">التنبيهات</h3>
                
                @if($inventory_alerts->count() > 0)
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800">تنبيه المخزون</p>
                            <p class="text-xs text-red-600">{{ $inventory_alerts->count() }} منتج يحتاج إلى إعادة تخزين</p>
                        </div>
                    </div>
                    
                    @foreach($inventory_alerts->take(3) as $product)
                    <div class="flex items-center p-2 border border-gray-200 rounded-lg">
                                                    <div class="w-8 h-8 rounded overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                            </div>
                        <div class="mr-2 flex-1">
                            <p class="text-xs font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">المخزون: {{ $product->stock }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                    <p class="text-sm text-gray-600">لا توجد تنبيهات</p>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="mt-6 space-y-2">
                    <a href="{{ route('admin.products.create') }}" class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i class="fas fa-plus ml-2"></i>إضافة منتج جديد
                    </a>
                    <a href="{{ route('admin.reports') }}" class="block w-full bg-gray-600 text-white text-center py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                        <i class="fas fa-file-alt ml-2"></i>عرض التقارير
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('period').addEventListener('change', function() {
    window.location.href = '{{ route("admin.dashboard") }}?period=' + this.value;
});
</script>
@endsection 