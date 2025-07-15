@extends('layouts.app')

@section('title', 'Fashion - Edit Category')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">تعديل الفئة</h1>
                    <p class="text-gray-600 mt-2">تعديل بيانات الفئة: {{ $category->name }}</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للفئات
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold mb-3 text-gray-700">
                        اسم الفئة *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم الفئة">
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold mb-3 text-gray-700">
                        الرابط المختصر *
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                           placeholder="example-category">
                    @error('slug')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold mb-3 text-gray-700">
                        وصف الفئة
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror"
                              placeholder="أدخل وصف الفئة...">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-semibold mb-3 text-gray-700">
                        صورة الفئة
                    </label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror"
                           onchange="previewImage(this, 'image-preview')">
                    <p class="text-xs text-gray-500 mt-1">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية</p>
                    @error('image')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                    
                    <!-- Current Image -->
                    @if($category->image)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-2">الصورة الحالية:</p>
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-3 hidden">
                        <p class="text-sm text-gray-600 mb-2">الصورة الجديدة:</p>
                        <img src="" alt="معاينة الصورة" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="mr-2 text-sm font-medium text-gray-700">فئة نشطة</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview image
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

// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slug').value = slug;
});
</script>
@endsection 