@extends('layouts.app')

@section('title', 'Fashion - Manage Categories')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إدارة الفئات</h1>
                    <p class="text-gray-600 mt-1">إدارة فئات المنتجات وتنظيمها</p>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-right ml-2"></i>العودة للوحة التحكم
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة فئة جديدة
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-th-large text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي الفئات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">فئات نشطة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeCategories }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-box text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المنتجات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="البحث في الفئات..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select id="status" name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div>
                    <label for="products_count" class="block text-sm font-medium text-gray-700 mb-2">المنتجات</label>
                    <select id="products_count" name="products_count" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الفئات</option>
                        <option value="with_products" {{ request('products_count') == 'with_products' ? 'selected' : '' }}>تحتوي على منتجات</option>
                        <option value="without_products" {{ request('products_count') == 'without_products' ? 'selected' : '' }}>لا تحتوي على منتجات</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search ml-2"></i>تطبيق الفلتر
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories Grid -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            @if($categories->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-white flex-shrink-0">
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mr-3">
                                        <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $category->slug }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $category->is_active ? 'fa-check' : 'fa-times' }} ml-1"></i>
                                    {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                @if($category->description)
                                    <p class="text-sm text-gray-600">{{ Str::limit($category->description, 100) }}</p>
                                @else
                                    <p class="text-sm text-gray-400">لا يوجد وصف</p>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-box ml-1"></i>
                                        {{ $category->products_count ?? 0 }} منتج
                                    </span>
                                </div>
                                
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit text-lg"></i>
                                    </a>
                                    <a href="{{ route('categories.show', $category) }}" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-900 transition-colors">
                                        <i class="fas fa-eye text-lg"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.delete', $category) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-th-large text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد فئات</h3>
                    <p class="text-gray-600 mb-4">ابدأ بإضافة فئة جديدة لتنظيم منتجاتك</p>
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة فئة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 