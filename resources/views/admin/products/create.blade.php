@extends('layouts.app')

@section('title', 'Fashion - Add New Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">إضافة منتج جديد</h1>
            <p class="text-gray-600">أدخل تفاصيل المنتج الجديد</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للمنتجات
        </a>
    </div>

    <!-- Product Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-8">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold mb-3 text-gray-700">
                            اسم المنتج *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required
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
                            <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
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
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}" min="0" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror"
                               placeholder="0">
                        @error('stock')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Main Image -->
                    <div>
                        <label for="image" class="block text-sm font-semibold mb-3 text-gray-700">
                            الصورة الرئيسية *
                        </label>
                        <div class="relative">
                            <input type="file" id="image" name="image" accept="image/*" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-image"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">اختر صورة بامتداد jpeg, png, jpg, gif بحجم أقل من 2MB</p>
                        @error('image')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        <!-- Image Preview -->
                        <div id="main-image-preview" class="mt-3 hidden">
                            <img src="#" alt="معاينة الصورة" class="w-32 h-32 object-cover rounded-lg border border-gray-200" style="display: none;">
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
                              placeholder="أدخل وصف المنتج...">{{ old('description') }}</textarea>
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
                        <input type="text" id="sizes" name="sizes" value="{{ old('sizes') }}"
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
                        <input type="text" id="colors" name="colors" value="{{ old('colors') }}"
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
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="mr-2 text-sm font-medium text-gray-700">منتج مميز</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="mr-2 text-sm font-medium text-gray-700">نشط</span>
                    </label>
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand" class="block text-sm font-semibold mb-3 text-gray-700">الماركة</label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('brand') border-red-500 @enderror"
                           placeholder="اسم الماركة">
                    @error('brand')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Material -->
                <div>
                    <label for="material" class="block text-sm font-semibold mb-3 text-gray-700">الخامة</label>
                    <input type="text" id="material" name="material" value="{{ old('material') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('material') border-red-500 @enderror"
                           placeholder="نوع الخامة">
                    @error('material')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-semibold mb-3 text-gray-700">اللون</label>
                    <input type="text" id="color" name="color" value="{{ old('color') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('color') border-red-500 @enderror"
                           placeholder="لون المنتج">
                    @error('color')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Size -->
                <div>
                    <label for="size" class="block text-sm font-semibold mb-3 text-gray-700">المقاس</label>
                    <input type="text" id="size" name="size" value="{{ old('size') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('size') border-red-500 @enderror"
                           placeholder="مقاس المنتج">
                    @error('size')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save ml-2"></i>
                        حفظ المنتج
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
// Preview single image (مبسط)
document.getElementById('image').addEventListener('change', function (evt) {
    const [file] = this.files;
    const previewDiv = document.getElementById('main-image-preview');
    const previewImg = previewDiv.querySelector('img');
    if (file) {
        previewImg.src = URL.createObjectURL(file);
        previewImg.style.display = 'block';
        previewDiv.classList.remove('hidden');
        previewImg.onload = function() {
            URL.revokeObjectURL(previewImg.src);
        }
    } else {
        previewImg.src = '#';
        previewImg.style.display = 'none';
        previewDiv.classList.add('hidden');
    }
});

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
    
    console.log('=== بدء مراقبة عملية رفع الصورة ===');
    
    // مراقبة اختيار الصورة
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
        if (file) {
            console.log('تم اختيار صورة:', {
                name: file.name,
                size: file.size + ' bytes',
                type: file.type,
                lastModified: new Date(file.lastModified)
            });
        }
    });
    
    // مراقبة إرسال النموذج
    form.addEventListener('submit', function(e) {
        console.log('=== بدء إرسال النموذج ===');
        
        const formData = new FormData(form);
        const imageFile = imageInput.files[0];
        
        if (imageFile) {
            console.log('تفاصيل الصورة المراد رفعها:', {
                name: imageFile.name,
                size: imageFile.size + ' bytes',
                type: imageFile.type
            });
        } else {
            console.warn('لم يتم اختيار صورة!');
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
        // لا نمنع الإرسال الطبيعي، فقط نضيف مراقبة
        setTimeout(function() {
            console.log('تم إرسال النموذج بنجاح');
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