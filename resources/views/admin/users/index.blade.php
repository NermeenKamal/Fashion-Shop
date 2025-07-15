@extends('layouts.app')

@section('title', 'Fashion - Manage Users')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إدارة المستخدمين</h1>
                    <p class="text-gray-600 mt-1">إدارة حسابات المستخدمين والصلاحيات</p>
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المستخدمين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">المستخدمين العاديين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $regularUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">المدراء</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">مستخدمين نشطين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="البحث بالاسم أو البريد الإلكتروني..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الدور</label>
                    <select id="role" name="role" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الأدوار</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>مستخدم</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>مدير</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select id="status" name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
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

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">معلومات الاتصال</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التسجيل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آخر تسجيل دخول</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                            @if($user->profile_image)
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <i class="fas fa-user text-blue-600"></i>
                                            @endif
                                        </div>
                                        <div class="mr-3">
                                            <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                        @if($user->phone)
                                            <p class="text-xs text-gray-500">{{ $user->phone }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} ml-1"></i>
                                        {{ $user->role === 'admin' ? 'مدير' : 'مستخدم' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $user->status === 'active' ? 'fa-check' : 'fa-times' }} ml-1"></i>
                                        {{ $user->status === 'active' ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <p>{{ $user->created_at->format('Y-m-d') }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->last_login_at)
                                        <p>{{ $user->last_login_at->format('Y-m-d') }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->last_login_at->diffForHumans() }}</p>
                                    @else
                                        <span class="text-gray-400">لم يسجل دخول بعد</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <div class="relative">
                                            <button type="button" 
                                                    onclick="toggleRoleMenu({{ $user->id }})"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                                <i class="fas fa-user-edit text-lg"></i>
                                            </button>
                                            <div id="role-menu-{{ $user->id }}" 
                                                 class="hidden absolute left-0 mt-2 w-32 bg-white rounded-xl shadow-lg border border-gray-200 z-10">
                                                <div class="py-1">
                                                    <button onclick="updateRole({{ $user->id }}, 'user')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $user->role === 'user' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                        <i class="fas fa-user ml-2"></i>مستخدم
                                                    </button>
                                                    <button onclick="updateRole({{ $user->id }}, 'admin')" 
                                                            class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $user->role === 'admin' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                        <i class="fas fa-user-shield ml-2"></i>مدير
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('profile.show', $user) }}" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-900 transition-colors">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مستخدمين</h3>
                    <p class="text-gray-600">لم يتم العثور على أي مستخدمين تطابق معايير البحث</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleRoleMenu(userId) {
    const menu = document.getElementById(`role-menu-${userId}`);
    const allMenus = document.querySelectorAll('[id^="role-menu-"]');
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m.id !== `role-menu-${userId}`) {
            m.classList.add('hidden');
        }
    });
    
    // Toggle current menu
    menu.classList.toggle('hidden');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    const menus = document.querySelectorAll('[id^="role-menu-"]');
    menus.forEach(menu => {
        if (!menu.contains(event.target) && !event.target.closest('button')) {
            menu.classList.add('hidden');
        }
    });
});

function updateRole(userId, role) {
    const roleNames = {
        'user': 'مستخدم',
        'admin': 'مدير'
    };
    
    if (confirm(`هل أنت متأكد من تغيير دور المستخدم إلى "${roleNames[role]}"؟`)) {
        fetch(`/admin/users/${userId}/role`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ role: role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تحديث دور المستخدم');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحديث دور المستخدم');
        });
    }
}
</script>
@endsection 