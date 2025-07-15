@extends('layouts.app')

@section('title', 'Fashion - Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">الملف الشخصي</h1>
            <p class="text-gray-600">إدارة معلوماتك الشخصية وتفضيلاتك</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <!-- Avatar Section -->
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-4 border-blue-100">
                                    <i class="fas fa-user text-white text-3xl"></i>
                                </div>
                            @endif
                            <div class="absolute bottom-0 right-0 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mt-4">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mt-2">
                                <i class="fas fa-crown ml-1"></i>مدير النظام
                            </span>
                        @endif
                    </div>

                    <!-- Quick Stats -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-bag text-blue-600 ml-2"></i>
                                <span class="text-sm font-medium text-gray-700">إجمالي الطلبات</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $user->orders()->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-green-600 ml-2"></i>
                                <span class="text-sm font-medium text-gray-700">تاريخ التسجيل</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('profile.edit') }}" class="block w-full bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit ml-2"></i>تعديل الملف الشخصي
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="block w-full bg-gray-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                            <i class="fas fa-key ml-2"></i>تغيير كلمة المرور
                        </a>
                        <a href="{{ route('profile.preferences') }}" class="block w-full bg-purple-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                            <i class="fas fa-cog ml-2"></i>التفضيلات
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">المعلومات الشخصية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <p class="text-gray-900 font-medium">{{ $user->phone ?? 'غير محدد' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                            <p class="text-gray-900 font-medium">{{ $user->address ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">آخر الطلبات</h3>
                        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            عرض الكل
                        </a>
                    </div>
                    
                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-blue-600"></i>
                                    </div>
                                    <div class="mr-4">
                                        <p class="font-semibold text-gray-900">طلب #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <p class="font-semibold text-gray-900">{{ number_format($order->final_amount, 2) }} د.ل</p>
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
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shopping-bag text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600 mb-4">لم تقم بإجراء أي طلبات بعد</p>
                            <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                <i class="fas fa-shopping-bag ml-2"></i>ابدأ التسوق
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Account Security -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">أمان الحساب</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-600 ml-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">كلمة المرور</p>
                                    <p class="text-sm text-gray-600">آخر تحديث: {{ $user->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('profile.change-password') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                تحديث
                            </a>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-bell text-blue-600 ml-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">الإشعارات</p>
                                    <p class="text-sm text-gray-600">إدارة إعدادات الإشعارات</p>
                                </div>
                            </div>
                            <a href="{{ route('profile.preferences') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                إعدادات
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 