@extends('layouts.app')

@section('title', 'Fashion - Preferences')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-right text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">التفضيلات</h1>
            </div>
            <p class="text-gray-600">إدارة إعدادات حسابك وتفضيلاتك</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <form action="{{ route('profile.preferences.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Language Settings -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">إعدادات اللغة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-2">اللغة المفضلة</label>
                            <select id="language" name="language" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="ar" {{ old('language', $user->language ?? 'ar') === 'ar' ? 'selected' : '' }}>العربية</option>
                                <option value="en" {{ old('language', $user->language ?? 'ar') === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">العملة المفضلة</label>
                            <select id="currency" name="currency" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="LYD" {{ old('currency', $user->currency ?? 'LYD') === 'LYD' ? 'selected' : '' }}>دينار ليبي (LYD)</option>
                                <option value="SAR" {{ old('currency', $user->currency ?? 'LYD') === 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                                <option value="USD" {{ old('currency', $user->currency ?? 'LYD') === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                                <option value="EUR" {{ old('currency', $user->currency ?? 'LYD') === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">إعدادات الإشعارات</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-600 ml-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">إشعارات البريد الإلكتروني</p>
                                    <p class="text-sm text-gray-600">استلام إشعارات عبر البريد الإلكتروني</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications[email]" value="1" 
                                       {{ old('notifications.email', json_decode($user->preferences ?? '{}')->email ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-sms text-green-600 ml-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">إشعارات الرسائل النصية</p>
                                    <p class="text-sm text-gray-600">استلام إشعارات عبر الرسائل النصية</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications[sms]" value="1" 
                                       {{ old('notifications.sms', json_decode($user->preferences ?? '{}')->sms ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-bell text-purple-600 ml-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">إشعارات الموقع</p>
                                    <p class="text-sm text-gray-600">استلام إشعارات في المتصفح</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications[push]" value="1" 
                                       {{ old('notifications.push', json_decode($user->preferences ?? '{}')->push ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">إعدادات الخصوصية</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-eye text-gray-600 ml-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">الملف الشخصي العام</p>
                                    <p class="text-sm text-gray-600">السماح للآخرين برؤية معلوماتك الأساسية</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="privacy[public_profile]" value="1" 
                                       {{ old('privacy.public_profile', json_decode($user->preferences ?? '{}')->public_profile ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('profile.show') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times ml-2"></i>إلغاء
                    </a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>حفظ التفضيلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 