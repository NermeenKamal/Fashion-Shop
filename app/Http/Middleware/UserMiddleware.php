<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        $user = Auth::user();

        // التحقق من أن المستخدم ليس مدير (للمسارات المخصصة للمستخدمين العاديين)
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'أنت مدير، يرجى استخدام لوحة الإدارة');
        }

        // التحقق من حالة الحساب
        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'تم تعليق حسابك. يرجى التواصل مع الإدارة.');
        }

        // التحقق من التحقق من البريد الإلكتروني
        if (!$user->email_verified_at && $request->route()->getName() !== 'verification.notice') {
            return redirect()->route('verification.notice')->with('warning', 'يرجى التحقق من بريدك الإلكتروني أولاً');
        }

        // تحديث آخر نشاط
        $user->update(['last_activity_at' => now()]);

        // إضافة معلومات المستخدم للـ request
        $request->attributes->add(['regular_user' => $user]);

        return $next($request);
    }
} 