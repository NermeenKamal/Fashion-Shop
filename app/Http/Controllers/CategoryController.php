<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * عرض جميع الفئات
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->get();

        // جلب المنتجات المميزة فقط
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        return view('categories.index', compact('categories', 'featuredProducts'));
    }

    /**
     * عرض المنتجات في فئة معينة
     */
    public function show(Category $category, Request $request)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $query = Product::with('category')
            ->where('category_id', $category->id)
            ->where('is_active', true);

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
                  ->orWhere('description', 'like', "%{$search}%");
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

        // الفئات الأخرى
        $otherCategories = Category::withCount('products')
            ->where('id', '!=', $category->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('categories.show', compact('category', 'products', 'otherCategories'));
    }
} 