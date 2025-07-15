<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard(Request $request)
    {
        $period = $request->get('period', 'month');

        // Calculate date range based on period
        $endDate = now();
        switch ($period) {
            case 'week':
                $startDate = now()->subWeek();
                $previousStartDate = now()->subWeeks(2);
                $previousEndDate = now()->subWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $previousStartDate = now()->subMonth()->startOfMonth();
                $previousEndDate = now()->subMonth()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $previousStartDate = now()->subYear()->startOfMonth();
                $previousEndDate = now()->subYear()->endOfMonth();
                break;
            default:
                $startDate = now()->startOfMonth();
                $previousStartDate = now()->subMonth()->startOfMonth();
                $previousEndDate = now()->subMonth()->endOfMonth();
        }

        // Current period stats
        $currentOrders = Order::whereBetween('created_at', [$startDate, $endDate]);
        $currentSales = $currentOrders->sum('final_amount');
        $currentOrdersCount = $currentOrders->count();
        $averageOrder = $currentOrdersCount > 0 ? $currentSales / $currentOrdersCount : 0;

        // Previous period stats for comparison
        $previousOrders = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate]);
        $previousSales = $previousOrders->sum('final_amount');
        $previousOrdersCount = $previousOrders->count();
        $previousAverageOrder = $previousOrdersCount > 0 ? $previousSales / $previousOrdersCount : 0;

        // Calculate growth rates
        $salesGrowth = $previousSales > 0 ? (($currentSales - $previousSales) / $previousSales) * 100 : 0;
        $ordersGrowth = $previousOrdersCount > 0 ? (($currentOrdersCount - $previousOrdersCount) / $previousOrdersCount) * 100 : 0;
        $avgOrderGrowth = $previousAverageOrder > 0 ? (($averageOrder - $previousAverageOrder) / $previousAverageOrder) * 100 : 0;

        $sales_stats = [
            'total_sales' => $currentSales,
            'total_orders' => $currentOrdersCount,
            'average_order' => $averageOrder,
            'growth_rate' => $salesGrowth,
            'orders_growth' => $ordersGrowth,
            'avg_order_growth' => $avgOrderGrowth,
        ];

        // User stats
        $totalUsers = User::count();
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $user_stats = [
            'total_users' => $totalUsers,
            'new_users' => $newUsers,
        ];

        // Order status distribution
        $order_status_distribution = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Top selling products
        // لحل مشكلة الغموض في عمود price، يجب تحديد الجدول الذي ينتمي إليه price (order_items.price)
        // To fix the ambiguous 'price' column, specify the table: order_items.price
        $top_products = OrderItem::selectRaw('
                order_items.product_id,
                SUM(order_items.quantity) as total_sold,
                SUM(order_items.quantity * order_items.price) as total_revenue,
                products.name as product_name
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('order_items.product_id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Recent orders
        $recent_orders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Inventory alerts (products with low stock)
        $inventory_alerts = Product::where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'sales_stats',
            'user_stats',
            'order_status_distribution',
            'top_products',
            'recent_orders',
            'inventory_alerts'
        ));
    }

    /**
     * Show all orders
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        // Get stats for the filtered results
        $totalOrders = $orders->total();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $deliveredOrders = $orders->where('status', 'delivered')->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();

        return view('admin.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'deliveredOrders', 'cancelledOrders'));
    }

    /**
     * Show order details
     */
    public function orderDetails(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'status_updated_at' => now()
        ]);

        // Add status history if the model has it
        if (method_exists($order, 'statusHistory')) {
            $order->statusHistory()->create([
                'status' => $newStatus,
                'changed_by' => auth()->id(),
                'notes' => "تم تغيير الحالة من {$oldStatus} إلى {$newStatus}"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطلب بنجاح',
            'new_status' => $newStatus
        ]);
    }

    /**
     * Show all products
     */
    public function products(Request $request)
    {
        $query = Product::with('category');

        // Search by name, description, or SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('stock', '>', 0);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<', 10);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        }

        $products = $query->latest()->paginate(20);

        // Get stats for the filtered results
        $totalProducts = $products->total();
        $activeProducts = $products->where('is_active', true)->count();
        $lowStockProducts = $products->where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = $products->where('stock', 0)->count();

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'totalProducts', 'activeProducts', 'lowStockProducts', 'outOfStockProducts'));
    }

    /**
     * Show create product form
     */
    public function createProduct()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'nullable',
            'colors' => 'nullable',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // إنشاء الرابط المختصر تلقائياً من اسم المنتج
        $data['slug'] = $this->generateSlug($request->name);

        // رفع الصورة الرئيسية
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // رفع الصور الإضافية محليًا فقط
        $additionalImages = [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('products/additional', 'public');
                $additionalImages[] = $imagePath;
            }
        }
        $data['images'] = $additionalImages;

        // معالجة الحقول النصية وتحويلها إلى array
        $data['sizes'] = $request->sizes ? array_map('trim', explode(',', $request->sizes)) : [];
        $data['colors'] = $request->colors ? array_map('trim', explode(',', $request->colors)) : [];

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * Generate unique slug from name
     */
    private function generateSlug($name)
    {
        $slug = Str::slug($name, '-', 'ar');
        $originalSlug = $slug;
        $counter = 1;

        // التحقق من عدم تكرار الرابط المختصر
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Show edit product form
     */
    public function editProduct(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validationRules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sizes' => 'nullable',
            'colors' => 'nullable',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];

        // إضافة التحقق من الصورة فقط إذا تم رفع ملف جديد
        if ($request->hasFile('image')) {
            $validationRules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        if ($request->hasFile('additional_images')) {
            $validationRules['additional_images.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $request->validate($validationRules);

        $data = $request->except(['image', 'additional_images']); // استبعاد حقول الصور من البيانات

        // إنشاء الرابط المختصر تلقائياً من اسم المنتج إذا تغير الاسم
        if ($request->name !== $product->name) {
            $data['slug'] = $this->generateSlug($request->name);
        }

        // رفع الصورة الرئيسية الجديدة إذا تم اختيارها
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // رفع الصور الإضافية الجديدة محليًا فقط
        if ($request->hasFile('additional_images')) {
            $additionalImages = [];
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('products/additional', 'public');
                $additionalImages[] = $imagePath;
            }
            $data['images'] = $additionalImages;
        }

        // معالجة الحقول النصية وتحويلها إلى array
        $data['sizes'] = $request->sizes ? array_map('trim', explode(',', $request->sizes)) : [];
        $data['colors'] = $request->colors ? array_map('trim', explode(',', $request->colors)) : [];

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Delete product
     */
    public function deleteProduct(Product $product)
    {
        // حذف الصورة الرئيسية
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // حذف الصور الإضافية
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->delete();
        return back()->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * Show all categories
     */
    public function categories(Request $request)
    {
        $query = Category::withCount('products');

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by products count
        if ($request->filled('products_count')) {
            switch ($request->products_count) {
                case 'with_products':
                    $query->having('products_count', '>', 0);
                    break;
                case 'without_products':
                    $query->having('products_count', 0);
                    break;
            }
        }

        $categories = $query->latest()->paginate(20);

        // Get stats for the filtered results
        $totalCategories = $categories->total();
        $activeCategories = $categories->where('is_active', true)->count();
        $totalProducts = $categories->sum('products_count');

        return view('admin.categories.index', compact('categories', 'totalCategories', 'activeCategories', 'totalProducts'));
    }

    /**
     * Show create category form
     */
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    /**
     * Store new category
     */
    public function storeCategory(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        if ($request->hasFile('image')) {
            $validationRules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $request->validate($validationRules);

        $data = $request->except(['image']);

        // رفع صورة الفئة إذا تم اختيارها
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'تم إنشاء الفئة بنجاح');
    }

    /**
     * Show edit category form
     */
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        if ($request->hasFile('image')) {
            $validationRules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $request->validate($validationRules);

        $data = $request->except(['image']);

        // رفع صورة الفئة الجديدة إذا تم اختيارها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Delete category
     */
    public function deleteCategory(Category $category)
    {
        // حذف صورة الفئة
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return back()->with('success', 'تم حذف الفئة بنجاح');
    }

    /**
     * Show all users
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by registration date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->latest()->paginate(20);

        // Get stats for the filtered results
        $totalUsers = $users->total();
        $regularUsers = $users->where('role', 'user')->count();
        $adminUsers = $users->where('role', 'admin')->count();
        $activeUsers = $users->where('status', 'active')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'regularUsers', 'adminUsers', 'activeUsers'));
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        // Prevent admin from changing their own role
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك تغيير دورك الخاص'
            ], 403);
        }

        $oldRole = $user->role;
        $newRole = $request->role;

        $user->update(['role' => $newRole]);

        return response()->json([
            'success' => true,
            'message' => "تم تحديث دور المستخدم من {$oldRole} إلى {$newRole} بنجاح",
            'new_role' => $newRole
        ]);
    }
}
