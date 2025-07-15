<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show cart
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, Product $product = null)
    {
        // إذا تم تمرير المنتج كمعامل في المسار
        if ($product) {
            $productId = $product->id;
            $quantity = $request->input('quantity', 1);
            $size = $request->input('size');
            $color = $request->input('color');
        } else {
            // إذا تم إرسال البيانات في النموذج
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'size' => 'nullable|string',
                'color' => 'nullable|string',
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity;
            $size = $request->size;
            $color = $request->color;
        }

        $product = Product::findOrFail($productId);

        // Check stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
        }

        // Check if item already exists in cart
        $existingItem = Auth::user()->cartItems()
            ->where('product_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
                'price' => $product->current_price,
            ]);
        } else {
            Auth::user()->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color,
                'price' => $product->current_price,
            ]);
        }

        return back()->with('success', 'تم إضافة المنتج إلى السلة بنجاح');
    }

    /**
     * Update cart item
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if cart item belongs to user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        // Check stock
        if ($cart->product->stock < $request->quantity) {
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
        }

        $cart->update([
            'quantity' => $request->quantity,
            'price' => $cart->product->current_price,
        ]);

        return back()->with('success', 'تم تحديث السلة بنجاح');
    }

    /**
     * Remove item from cart
     */
    public function remove(Cart $cart)
    {
        // Check if cart item belongs to user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'تم حذف المنتج من السلة بنجاح');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Auth::user()->cartItems()->delete();

        return back()->with('success', 'تم تفريغ السلة بنجاح');
    }
}
