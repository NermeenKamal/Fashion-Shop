<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/product/{slug}', [HomeController::class, 'product'])->name('product');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// المنتجات (عامة)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// الفئات (عامة)
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// المصادقة
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// الملف الشخصي
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password')->middleware('auth');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password')->middleware('auth');
Route::get('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences')->middleware('auth');
Route::put('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update')->middleware('auth');
Route::get('/profile/activity', [ProfileController::class, 'activity'])->name('profile.activity')->middleware('auth');
Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist')->middleware('auth');
Route::post('/profile/wishlist/add', [ProfileController::class, 'addToWishlist'])->name('profile.wishlist.add')->middleware('auth');
Route::delete('/profile/wishlist/remove', [ProfileController::class, 'removeFromWishlist'])->name('profile.wishlist.remove')->middleware('auth');
Route::post('/profile/wishlist/toggle/{product}', [ProfileController::class, 'addToWishlist'])->name('wishlist.toggle')->middleware('auth');

// السلة
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add.product')->middleware('auth');
Route::put('/cart/{cart}/update', [CartController::class, 'update'])->name('cart.update')->middleware('auth');
Route::delete('/cart/{cart}/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('auth');

// الطلبات
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel')->middleware('auth');

// لوحة الإدارة
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [DashboardController::class, 'exportOrders'])->name('reports.export');
    
    // الطلبات
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'orderDetails'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // المنتجات
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('admin.products.uploadImage');
    
    // الفئات
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    
    // المستخدمين
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
});
