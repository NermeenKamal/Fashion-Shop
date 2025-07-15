@extends('layouts.app')

@section('title', 'Fashion - Analytics')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">التحليلات</h1>
                    <p class="text-gray-600 mt-1">تحليل شامل لأداء المتجر</p>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-right ml-2"></i>العودة للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">المبيعات الشهرية</h3>
            @if(count($monthlySales['data']) > 0)
                <div class="h-80">
                    <canvas id="salesChart"></canvas>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-chart-line text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">لا توجد بيانات مبيعات متاحة</p>
                </div>
            @endif
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">نمو المستخدمين</h3>
            @if(count($userGrowth['data']) > 0)
                <div class="h-80">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">لا توجد بيانات مستخدمين متاحة</p>
                </div>
            @endif
        </div>

        <!-- Category Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">توزيع المنتجات حسب الفئة</h3>
                @if($categoryStats->count() > 0)
                    <div class="h-80">
                        <canvas id="categoryChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-chart-pie text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">لا توجد فئات متاحة</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">إحصائيات الفئات</h3>
                @if($categoryStats->count() > 0)
                    <div class="space-y-4">
                        @foreach($categoryStats as $category)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-blue-500 ml-3"></div>
                                <span class="font-medium text-gray-900">{{ $category->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900">{{ $category->product_count }}</span>
                                <p class="text-sm text-gray-500">منتج</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-th-large text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">لا توجد فئات متاحة</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(count($monthlySales['data']) > 0 || count($userGrowth['data']) > 0 || $categoryStats->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(count($monthlySales['data']) > 0)
// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: @json($monthlySales['labels']),
        datasets: [{
            label: 'المبيعات (د.ل)',
            data: @json($monthlySales['data']),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' د.ل';
                    }
                }
            }
        }
    }
});
@endif

@if(count($userGrowth['data']) > 0)
// User Growth Chart
const userCtx = document.getElementById('userGrowthChart').getContext('2d');
const userChart = new Chart(userCtx, {
    type: 'bar',
    data: {
        labels: @json($userGrowth['labels']),
        datasets: [{
            label: 'المستخدمين الجدد',
            data: @json($userGrowth['data']),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
@endif

@if($categoryStats->count() > 0)
// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json($categoryStats->pluck('name')),
        datasets: [{
            data: @json($categoryStats->pluck('product_count')),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
@endif
</script>
@endif
@endsection 