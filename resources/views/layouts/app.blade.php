<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo.ico') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @stack('styles')
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1000;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-item {
            display: block;
            padding: 12px 16px;
            text-decoration: none;
            color: #374151;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f3f4f6;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.2s;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            color: #a98142;
            background-color: #c3b392;
        }
        
        .nav-link.active {
            color: #a98142;
            background-color: #c3b392;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #a98142;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #c3b392;
        }
        
        .btn-ghost {
            background-color: transparent;
            color: #6b7280;
        }
        
        .btn-ghost:hover {
            background-color: #f3f4f6;
        }
        
        .input {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .input:focus {
            outline: none;
            border-color: #a98142;
            box-shadow: 0 0 0 3px rgba(169, 129, 66, 0.1);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        
        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-primary {
            background-color: #a98142;
            color: white;
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 12px;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="container">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 space-x-reverse">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
                            <span class="text-xl font-bold text-gray-900">Fashion Store</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-6 space-x-reverse">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home ml-2"></i>
                            الرئيسية
                        </a>
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag ml-2"></i>
                            المنتجات
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags ml-2"></i>
                            الفئات
                        </a>
                        @auth
                            <a href="{{ route('profile.wishlist') }}" class="relative nav-link" title="المفضلة">
                                <i class="fas fa-heart"></i>
                                @php $wishlistCount = auth()->user()->wishlist()->count(); @endphp
                                @if($wishlistCount > 0)
                                <span style="position:absolute;top:0;left:0;background:#a98142;color:#fff;border-radius:50%;font-size:12px;padding:2px 6px;line-height:1;">{{ $wishlistCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('cart.index') }}" class="nav-link" title="السلة">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                <i class="fas fa-receipt ml-2"></i>
                                طلباتي
                            </a>
                        @endauth
                    </div>

                    <!-- Search Bar -->
                    <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <input type="text" name="q" placeholder="البحث عن منتجات..." 
                                   class="input w-64" value="{{ request('q') }}">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        <!-- User Menu -->
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-ghost flex items-center gap-2">
                                    <i class="fas fa-user-circle text-xl"></i>
                                    <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                                        <i class="fas fa-user ml-2"></i>
                                        الملف الشخصي
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                            <i class="fas fa-cog ml-2"></i>
                                            لوحة التحكم
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="dropdown-item w-full text-right">
                                            <i class="fas fa-sign-out-alt ml-2"></i>
                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <a href="{{ route('login') }}" class="btn btn-ghost">
                                    <i class="fas fa-sign-in-alt ml-2"></i>
                                    تسجيل الدخول
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus ml-2"></i>
                                    إنشاء حساب
                                </a>
                            </div>
                        @endauth

                        <!-- Mobile Menu Button -->
                        <button class="btn btn-ghost md:hidden" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 py-4">
                    <div class="flex flex-col gap-4">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home ml-2"></i>
                            الرئيسية
                        </a>
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag ml-2"></i>
                            المنتجات
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags ml-2"></i>
                            الفئات
                        </a>
                        @auth
                            <a href="{{ route('profile.wishlist') }}" class="relative nav-link" title="المفضلة">
                                <i class="fas fa-heart"></i>
                                @php $wishlistCount = auth()->user()->wishlist()->count(); @endphp
                                @if($wishlistCount > 0)
                                <span style="position:absolute;top:0;left:0;background:#a98142;color:#fff;border-radius:50%;font-size:12px;padding:2px 6px;line-height:1;">{{ $wishlistCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('cart.index') }}" class="nav-link" title="السلة">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                <i class="fas fa-receipt ml-2"></i>
                                طلباتي
                            </a>
                        @endauth
                        
                        <!-- Mobile Search -->
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <input type="text" name="q" placeholder="البحث عن منتجات..." 
                                   class="input flex-1" value="{{ request('q') }}">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container py-8">
            @if(session('success'))
                <div class="alert alert-success fade-in">
                    <i class="fas fa-check-circle ml-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error fade-in">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning fade-in">
                    <i class="fas fa-exclamation-triangle ml-2"></i>
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info fade-in">
                    <i class="fas fa-info-circle ml-2"></i>
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error fade-in">
                    <div class="font-semibold mb-2">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        يرجى إصلاح الأخطاء التالية:
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white border-t border-gray-700 mt-auto">
            <div class="container py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="font-semibold text-lg mb-4">متجر الملابس</h3>
                        <p class="text-gray-300 mb-4">وجهتك الأولى للملابس العصرية والأنيقة.</p>
                        <div class="flex gap-4">
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">روابط سريعة</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">الرئيسية</a></li>
                            <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white transition-colors">المنتجات</a></li>
                            <li><a href="{{ route('categories.index') }}" class="text-gray-300 hover:text-white transition-colors">الفئات</a></li>
                            <li><a href="{{ route('search') }}" class="text-gray-300 hover:text-white transition-colors">البحث</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">خدمة العملاء</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">اتصل بنا</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">معلومات الشحن</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">المرتجعات</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">الأسئلة الشائعة</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">النشرة الإخبارية</h4>
                        <p class="text-gray-300 mb-4">اشترك للحصول على آخر التحديثات والعروض.</p>
                        <form class="flex">
                            <input type="email" placeholder="أدخل بريدك الإلكتروني" class="input rounded-l-none">
                            <button type="submit" class="btn btn-primary rounded-r-none">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-300">&copy; {{ date('Y') }} متجر الملابس. جميع الحقوق محفوظة.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @auth
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function showWishlistMsg(msg, btn) {
            let span = btn.parentElement.querySelector('.wishlist-msg');
            if (!span) {
                span = document.createElement('span');
                span.className = 'wishlist-msg';
                span.style.marginRight = '10px';
                span.style.fontSize = '15px';
                btn.parentElement.appendChild(span);
            }
            span.textContent = msg;
            span.style.display = 'inline';
            setTimeout(() => { span.style.display = 'none'; }, 2000);
        }
        document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const productId = btn.getAttribute('data-id');
                const heart = btn.querySelector('i.fas.fa-heart');
                fetch(`/profile/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        heart.style.color = data.in_wishlist ? '#a98142' : '#ccc';
                        showWishlistMsg(data.message, btn);
                    } else {
                        showWishlistMsg('حدث خطأ!', btn);
                    }
                })
                .catch(() => {
                    showWishlistMsg('حدث خطأ!', btn);
                });
            });
        });
    });
    </script>
    @endauth
</body>
</html> 