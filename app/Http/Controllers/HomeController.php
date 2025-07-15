<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page
     */
    public function index()
    {
        $featuredProducts = Product::active()->featured()->inStock()->take(8)->get();
        $categories = Category::active()->withCount('products')->get();
        $latestProducts = Product::active()->inStock()->latest()->take(12)->get();

        return view('home', compact('featuredProducts', 'categories', 'latestProducts'));
    }

    /**
     * Show products by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        $products = $category->activeProducts()->inStock()->paginate(12);

        return view('category', compact('category', 'products'));
    }

    /**
     * Show product details
     */
    public function product($slug)
    {
        $product = Product::where('slug', $slug)->active()->firstOrFail();
        
        // Increment views
        $product->increment('views');
        
        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = Product::active()
            ->inStock()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(12);

        return view('search', compact('products', 'query'));
    }
}
