<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->with('orderItems.product')->latest()->limit(5)->get();
        
        return view('profile.show', compact('user', 'recentOrders'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['profile_image'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.show')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    /**
     * Show user preferences
     */
    public function preferences()
    {
        $user = Auth::user();
        return view('profile.preferences', compact('user'));
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'language' => 'required|in:ar,en',
            'currency' => 'required|in:LYD,SAR,USD,EUR',
            'notifications' => 'array',
            'notifications.email' => 'boolean',
            'notifications.sms' => 'boolean',
            'notifications.push' => 'boolean',
        ]);

        $user = Auth::user();
        $user->update([
            'language' => $request->language,
            'currency' => $request->currency,
            'preferences' => json_encode($request->notifications ?? []),
        ]);

        return redirect()->route('profile.preferences')->with('success', 'تم تحديث التفضيلات بنجاح');
    }

    /**
     * Show user activity
     */
    public function activity()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.product')->latest()->paginate(10);
        
        return view('profile.activity', compact('user', 'orders'));
    }

    /**
     * Show user wishlist
     */
    public function wishlist()
    {
        $user = Auth::user();
        $wishlist = $user->wishlist()->paginate(12);
        
        return view('profile.wishlist', compact('user', 'wishlist'));
    }

    /**
     * Add product to wishlist
     */
    public function addToWishlist(Request $request, Product $product = null)
    {
        $user = auth()->user();
        $productId = $product ? $product->id : $request->product_id;
        $inWishlist = $user->wishlist()->where('product_id', $productId)->exists();
        if ($inWishlist) {
            $user->wishlist()->detach($productId);
            $message = 'تمت إزالة المنتج من المفضلة';
        } else {
            $user->wishlist()->attach($productId);
            $message = 'تم إضافة المنتج إلى المفضلة بنجاح';
        }
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'in_wishlist' => !$inWishlist,
            ]);
        }
        return back();
    }

    /**
     * Remove product from wishlist
     */
    public function removeFromWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $user->wishlist()->detach($request->product_id);

        return response()->json(['success' => true, 'message' => 'تم إزالة المنتج من المفضلة']);
    }
} 