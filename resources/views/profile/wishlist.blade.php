@extends('layouts.app')

@section('title', 'Fashion - Wishlist')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-right text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">المفضلة</h1>
            </div>
            <p class="text-gray-600">المنتجات التي أضفتها إلى قائمة المفضلة</p>
        </div>

        @if($wishlist->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlist as $item)
                    @component('components.product-card', ['product' => $item])
                    @endcomponent
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($wishlist->hasPages())
                <div class="mt-8">
                    {{ $wishlist->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">قائمة المفضلة فارغة</h3>
                <p class="text-gray-600 mb-6">لم تقم بإضافة أي منتجات إلى المفضلة بعد</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-shopping-bag ml-2"></i>تصفح المنتجات
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if (confirm('هل أنت متأكد من إزالة هذا المنتج من المفضلة؟')) {
        fetch(`/profile/wishlist/remove`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء إزالة المنتج من المفضلة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إزالة المنتج من المفضلة');
        });
    }
}

function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إضافة المنتج إلى السلة بنجاح');
        } else {
            alert(data.message || 'حدث خطأ أثناء إضافة المنتج إلى السلة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة المنتج إلى السلة');
    });
}
</script>
@endsection 