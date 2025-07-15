@extends('layouts.app')

@section('title', 'Fashion - Change Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-right text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">تغيير كلمة المرور</h1>
            </div>
            <p class="text-gray-600">قم بتحديث كلمة المرور الخاصة بك</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Current Password -->
                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية *</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                           required>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة *</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                           required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">يجب أن تكون كلمة المرور 8 أحرف على الأقل</p>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور الجديدة *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Password Requirements -->
                <div class="mb-8 p-4 bg-gray-50 rounded-xl">
                    <h4 class="font-medium text-gray-800 mb-3">متطلبات كلمة المرور:</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            يجب أن تكون 8 أحرف على الأقل
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            يجب أن تحتوي على حروف وأرقام
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            يجب أن تكون مختلفة عن كلمة المرور الحالية
                        </li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('profile.show') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times ml-2"></i>إلغاء
                    </a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-key ml-2"></i>تغيير كلمة المرور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 