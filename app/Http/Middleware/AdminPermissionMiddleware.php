<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        $user = Auth::user();

        // التحقق من أن المستخدم مدير
        if (!$user->isAdmin()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة. هذه الصفحة مخصصة للمديرين فقط.');
        }

        // التحقق من الصلاحية المحددة
        if (!$user->hasPermission($permission)) {
            \Log::warning('Admin permission denied', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'required_permission' => $permission,
                'url' => $request->fullUrl()
            ]);

            abort(403, 'ليس لديك الصلاحية المطلوبة للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
} 