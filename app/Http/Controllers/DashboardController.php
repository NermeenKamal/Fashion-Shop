<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $period = request('period', 'month');
        
        $data = [
            'sales_stats' => Dashboard::getSalesStats($period),
            'top_products' => Dashboard::getTopProducts(),
            'recent_orders' => Dashboard::getRecentOrders(),
            'order_status_distribution' => Dashboard::getOrderStatusDistribution(),
            'user_stats' => Dashboard::getUserStats(),
            'inventory_alerts' => Dashboard::getInventoryAlerts(),
            'period' => $period,
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Show analytics page
     */
    public function analytics()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // Get data for charts
        $monthlySales = $this->getMonthlySales();
        $categoryStats = $this->getCategoryStats();
        $userGrowth = $this->getUserGrowth();

        return view('admin.analytics', compact('monthlySales', 'categoryStats', 'userGrowth'));
    }

    /**
     * Get monthly sales data
     */
    private function getMonthlySales()
    {
        $months = [];
        $sales = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthSales = Order::where('status', '!=', 'cancelled')
                              ->where('payment_status', 'paid')
                              ->whereYear('created_at', $date->year)
                              ->whereMonth('created_at', $date->month)
                              ->sum('final_amount');
            
            $sales[] = $monthSales;
        }

        return [
            'labels' => $months,
            'data' => $sales
        ];
    }

    /**
     * Get category statistics
     */
    private function getCategoryStats()
    {
        return \DB::table('products')
                  ->join('categories', 'products.category_id', '=', 'categories.id')
                  ->select('categories.name', \DB::raw('COUNT(products.id) as product_count'))
                  ->groupBy('categories.id', 'categories.name')
                  ->get();
    }

    /**
     * Get user growth data
     */
    private function getUserGrowth()
    {
        $months = [];
        $users = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthUsers = User::whereYear('created_at', $date->year)
                             ->whereMonth('created_at', $date->month)
                             ->count();
            
            $users[] = $monthUsers;
        }

        return [
            'labels' => $months,
            'data' => $users
        ];
    }

    /**
     * Show reports page
     */
    public function reports()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $startDate = request('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                      ->with(['user', 'orderItems.product'])
                      ->get();

        $totalRevenue = $orders->where('payment_status', 'paid')->sum('final_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin.reports', compact('orders', 'totalRevenue', 'totalOrders', 'averageOrderValue', 'startDate', 'endDate'));
    }

    /**
     * Export orders report
     */
    public function exportOrders(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $startDate = $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $orders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                      ->with(['user', 'orderItems.product'])
                      ->get();

        // Generate CSV
        $filename = 'orders_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['رقم الطلب', 'العميل', 'التاريخ', 'الحالة', 'المبلغ', 'طريقة الدفع']);
            
            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->status,
                    $order->final_amount,
                    $order->payment_method
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 