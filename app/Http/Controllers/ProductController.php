<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * عرض جميع المنتجات
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // فلترة حسب الفئة
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // فلترة حسب السعر
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * عرض تفاصيل المنتج
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        // المنتجات ذات الصلة
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('products', 'public');
            $data['image'] = $path;
        }

        // معالجة المقاسات والألوان إذا كانت نصوص مفصولة بفواصل
        if ($request->filled('sizes')) {
            $data['sizes'] = array_map('trim', explode(',', $request->sizes));
        }
        if ($request->filled('colors')) {
            $data['colors'] = array_map('trim', explode(',', $request->colors));
        }

        // رفع الصور الإضافية محليًا فقط
        if ($request->hasFile('additional_images')) {
            $images = [];
            foreach ($request->file('additional_images') as $file) {
                $images[] = $file->store('products', 'public');
            }
            $data['images'] = $images;
        }

        $data['brand'] = $request->input('brand');
        $data['material'] = $request->input('material');
        $data['color'] = $request->input('color');
        $data['size'] = $request->input('size');

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $image = $request->file('image');
            $path = $image->store('products', 'public');
            $data['image'] = $path;
        }

        // معالجة المقاسات والألوان إذا كانت نصوص مفصولة بفواصل
        if ($request->filled('sizes')) {
            $data['sizes'] = array_map('trim', explode(',', $request->sizes));
        }
        if ($request->filled('colors')) {
            $data['colors'] = array_map('trim', explode(',', $request->colors));
        }

        // رفع الصور الإضافية محليًا فقط عند التعديل
        if ($request->hasFile('additional_images')) {
            $images = [];
            foreach ($request->file('additional_images') as $file) {
                $images[] = $file->store('products', 'public');
            }
            $data['images'] = $images;
        }

        $data['brand'] = $request->input('brand');
        $data['material'] = $request->input('material');
        $data['color'] = $request->input('color');
        $data['size'] = $request->input('size');

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $path = $request->file('image')->store('products', 'public');
        return response()->json(['path' => $path, 'url' => asset('storage/' . $path)]);
    }
}
