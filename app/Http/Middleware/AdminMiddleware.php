<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        // التحقق من أن المستخدم مدير
        if (!$user->isAdmin()) {
            // تسجيل محاولة الوصول غير المصرح
            \Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);

            abort(403, 'غير مصرح لك بالوصول إلى لوحة الإدارة. هذه الصفحة مخصصة للمديرين فقط.');
        }

        // التحقق من حالة الحساب
        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'تم تعليق حسابك. يرجى التواصل مع الإدارة.');
        }

        // التحقق من آخر نشاط للمدير
        if ($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) > 30) {
            // تحديث آخر نشاط
            $user->update(['last_activity_at' => now()]);
        }

        // إضافة معلومات المدير للـ request
        $request->attributes->add(['admin_user' => $user]);

        return $next($request);
    }
}
