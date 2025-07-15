@extends('layouts.app')

@section('title', 'Fashion - Edit Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">تعديل المنتج</h1>
            <p class="text-gray-600">تعديل تفاصيل المنتج: {{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للمنتجات
        </a>
    </div>

    <!-- Product Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-8">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold mb-3 text-gray-700">
                            اسم المنتج *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="أدخل اسم المنتج">
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold mb-3 text-gray-700">
                            الفئة *
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold mb-3 text-gray-700">
                            السعر الأصلي *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">د.ل</span>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="block text-sm font-semibold mb-3 text-gray-700">
                            سعر البيع
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">د.ل</span>
                            <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sale_price') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('sale_price')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-semibold mb-3 text-gray-700">
                            المخزون *
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror"
                               placeholder="0">
                        @error('stock')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Main Image -->
                    <div>
                        <label for="image" class="block text-sm font-semibold mb-3 text-gray-700">
                            الصورة الرئيسية
                        </label>
                        <div class="relative">
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror"
                                   onchange="previewImage(this, 'main-image-preview')">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-image"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية</p>
                        @error('image')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        <!-- Current Image -->
                        @if($product->image_url)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">الصورة الحالية:</p>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                        @else
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">لا توجد صورة حالية</p>
                            <div class="w-32 h-32 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200">
                                <i class="fas fa-image text-2xl text-gray-400"></i>
                            </div>
                        </div>
                        @endif
                        <!-- Image Preview -->
                        <div id="main-image-preview" class="mt-3 hidden">
                            <p class="text-sm text-gray-600 mb-2">الصورة الجديدة:</p>
                            <img src="" alt="معاينة الصورة" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold mb-3 text-gray-700">
                        وصف المنتج *
                    </label>
                    <textarea id="description" name="description" rows="6" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror"
                              placeholder="أدخل وصف المنتج...">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Images -->
                <div>
                    <label for="additional_images" class="block text-sm font-semibold mb-3 text-gray-700">
                        صور إضافية
                    </label>
                    <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('additional_images') border-red-500 @enderror"
                           onchange="previewMultipleImages(this, 'additional-images-preview')">
                    @error('additional_images')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                    
                    <!-- Current Additional Images -->
                    @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-2">الصور الإضافية الحالية:</p>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach($product->images as $image)
                            <div class="relative">
                                <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" alt="صورة إضافية" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Additional Images Preview -->
                    <div id="additional-images-preview" class="mt-3 grid grid-cols-4 gap-3 hidden">
                    </div>
                </div>

                <!-- Sizes and Colors -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sizes" class="block text-sm font-semibold mb-3 text-gray-700">
                            المقاسات (مفصولة بفواصل)
                        </label>
                        <input type="text" id="sizes" name="sizes" value="{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sizes') border-red-500 @enderror"
                               placeholder="S, M, L, XL">
                        @error('sizes')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="colors" class="block text-sm font-semibold mb-3 text-gray-700">
                            الألوان (مفصولة بفواصل)
                        </label>
                        <input type="text" id="colors" name="colors" value="{{ old('colors', is_array($product->colors) ? implode(', ', $product->colors) : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('colors') border-red-500 @enderror"
                               placeholder="أحمر, أزرق, أخضر">
                        @error('colors')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="mr-2 text-sm font-medium text-gray-700">منتج مميز</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="mr-2 text-sm font-medium text-gray-700">نشط</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview single image
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

// Preview multiple images
function previewMultipleImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = `صورة ${index + 1}`;
                img.className = 'w-24 h-24 object-cover rounded-lg border border-gray-200';
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// مراقبة عملية رفع الصورة وإظهار التفاصيل في console
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const imageInput = document.getElementById('image');
    
    console.log('=== بدء مراقبة عملية تعديل المنتج ===');
    
    // مراقبة اختيار الصورة الجديدة
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            console.log('تم اختيار صورة جديدة:', {
                name: file.name,
                size: file.size + ' bytes',
                type: file.type,
                lastModified: new Date(file.lastModified)
            });
        }
    });
    
    // مراقبة إرسال النموذج
    form.addEventListener('submit', function(e) {
        console.log('=== بدء إرسال نموذج التعديل ===');
        
        const formData = new FormData(form);
        const imageFile = imageInput.files[0];
        
        if (imageFile) {
            console.log('تفاصيل الصورة الجديدة المراد رفعها:', {
                name: imageFile.name,
                size: imageFile.size + ' bytes',
                type: imageFile.type
            });
        } else {
            console.log('لم يتم اختيار صورة جديدة - سيتم الاحتفاظ بالصورة الحالية');
        }
        
        // مراقبة البيانات المرسلة
        console.log('بيانات النموذج المرسلة:');
        for (let [key, value] of formData.entries()) {
            if (key === 'image') {
                console.log(key + ':', value.name + ' (' + value.size + ' bytes)');
            } else {
                console.log(key + ':', value);
            }
        }
        
        console.log('جاري إرسال النموذج إلى:', form.action);
    });
    
    // مراقبة الاستجابة من السيرفر
    form.addEventListener('submit', function(e) {
        setTimeout(function() {
            console.log('تم إرسال نموذج التعديل بنجاح');
        }, 100);
    });
});

// مراقبة أي أخطاء في الصفحة
window.addEventListener('error', function(e) {
    console.error('خطأ في الصفحة:', e.error);
});

// مراقبة أخطاء الشبكة
window.addEventListener('unhandledrejection', function(e) {
    console.error('خطأ في الشبكة:', e.reason);
});
</script>
@endsection 