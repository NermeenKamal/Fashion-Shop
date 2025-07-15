<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Model
{
    /**
     * Get sales statistics
     */
    public static function getSalesStats($period = 'month')
    {
        $endDate = now();
        
        switch ($period) {
            case 'week':
                $startDate = now()->startOfWeek();
                $previousStartDate = now()->subWeeks(2)->startOfWeek();
                $previousEndDate = now()->subWeek()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $previousStartDate = now()->subMonth()->startOfMonth();
                $previousEndDate = now()->subMonth()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $previousStartDate = now()->subYear()->startOfMonth();
                $previousEndDate = now()->subYear()->endOfMonth();
                break;
            default:
                $startDate = now()->startOfMonth();
                $previousStartDate = now()->subMonth()->startOfMonth();
                $previousEndDate = now()->subMonth()->endOfMonth();
        }

        // Current period stats
        $currentOrders = Order::whereBetween('created_at', [$startDate, $endDate]);
        $currentSales = $currentOrders->sum('final_amount');
        $currentOrdersCount = $currentOrders->count();
        $averageOrder = $currentOrdersCount > 0 ? $currentSales / $currentOrdersCount : 0;

        // Previous period stats for comparison
        $previousOrders = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate]);
        $previousSales = $previousOrders->sum('final_amount');
        $previousOrdersCount = $previousOrders->count();
        $previousAverageOrder = $previousOrdersCount > 0 ? $previousSales / $previousOrdersCount : 0;

        // Calculate growth rates
        $salesGrowth = $previousSales > 0 ? (($currentSales - $previousSales) / $previousSales) * 100 : 0;
        $ordersGrowth = $previousOrdersCount > 0 ? (($currentOrdersCount - $previousOrdersCount) / $previousOrdersCount) * 100 : 0;
        $avgOrderGrowth = $previousAverageOrder > 0 ? (($averageOrder - $previousAverageOrder) / $previousAverageOrder) * 100 : 0;

        return [
            'total_sales' => $currentSales,
            'total_orders' => $currentOrdersCount,
            'average_order' => $averageOrder,
            'growth_rate' => $salesGrowth,
            'orders_growth' => $ordersGrowth,
            'avg_order_growth' => $avgOrderGrowth,
        ];
    }

    /**
     * Get top selling products
     */
    public static function getTopProducts($limit = 5)
    {
        return OrderItem::selectRaw('
                product_id,
                SUM(quantity) as total_sold,
                SUM(quantity * price) as total_revenue,
                products.name as product_name
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('product_id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent orders
     */
    public static function getRecentOrders($limit = 10)
    {
        return Order::with(['user', 'orderItems.product'])
                   ->latest()
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get order status distribution
     */
    public static function getOrderStatusDistribution()
    {
        return Order::select('status', DB::raw('count(*) as count'))
                   ->groupBy('status')
                   ->pluck('count', 'status')
                   ->toArray();
    }

    /**
     * Get user statistics
     */
    public static function getUserStats()
    {
        $startDate = now()->startOfMonth();
        $endDate = now();
        
        return [
            'total_users' => User::count(),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_users' => User::whereHas('orders', function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            })->count(),
        ];
    }

    /**
     * Get inventory alerts
     */
    public static function getInventoryAlerts()
    {
        return Product::where('stock', '<', 10)
                     ->where('stock', '>', 0)
                     ->limit(5)
                     ->get();
    }
} 