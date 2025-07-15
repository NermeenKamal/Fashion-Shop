<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $total = $cartItems->sum('subtotal');
        $shippingCost = 50; // تكلفة الشحن الثابتة
        $taxAmount = $total * 0.15; // ضريبة 15%
        $finalAmount = $total + $shippingCost + $taxAmount;

        return view('orders.checkout', compact('cartItems', 'total', 'shippingCost', 'taxAmount', 'finalAmount'));
    }

    /**
     * Place order
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'billing_address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            'notes' => 'nullable|string',
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        // Check stock for all items
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "الكمية المطلوبة غير متوفرة للمنتج: {$item->product->name}");
            }
        }

        try {
            DB::beginTransaction();

            $total = $cartItems->sum('subtotal');
            $shippingCost = 50;
            $taxAmount = $total * 0.15;
            $finalAmount = $total + $shippingCost + $taxAmount;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $total,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_image' => $item->product->image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'size' => $item->size,
                    'color' => $item->color,
                    'subtotal' => $item->subtotal,
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'amount' => $finalAmount,
                'status' => 'pending',
            ]);

            // Clear cart
            Auth::user()->cartItems()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'تم إنشاء الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إنشاء الطلب');
        }
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load relationships
        $order->load(['orderItems.product', 'payment']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show user orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with(['orderItems.product'])->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب');
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => 'cancelled']);

            // Restore product stock
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            DB::commit();

            return back()->with('success', 'تم إلغاء الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إلغاء الطلب');
        }
    }
}
