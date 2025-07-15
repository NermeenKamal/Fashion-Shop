@extends('layouts.app')

@section('title', 'Fashion - ' . $category->name)

@section('content')
<div class="space-y-8">
    <!-- Category Header -->
    <div class="text-center">
        <div class="w-24 h-24 mx-auto mb-4 bg-primary/10 rounded-full flex items-center justify-center">
            <i class="fas fa-tshirt text-3xl text-primary"></i>
        </div>
        <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
        <p class="text-muted mt-2">{{ $category->description }}</p>
        <p class="text-sm text-muted mt-1">{{ $products->total() }} products found</p>
    </div>

    <!-- Filters and Products -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Filters</h3>
                </div>
                <div class="card-content p-6">
                    <!-- Price Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3">Price Range</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm text-muted mb-1">Min Price</label>
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}"
                                       placeholder="0"
                                       class="input"
                                       onchange="applyFilters()">
                            </div>
                            <div>
                                <label class="block text-sm text-muted mb-1">Max Price</label>
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}"
                                       placeholder="1000"
                                       class="input"
                                       onchange="applyFilters()">
                            </div>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3">Sort By</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="sort" 
                                       value="newest"
                                       {{ request('sort') == 'newest' ? 'checked' : '' }}
                                       onchange="applyFilters()"
                                       class="w-4 h-4 text-primary border-border focus:ring-primary focus:ring-2">
                                <span class="ml-2 text-sm">Newest First</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="sort" 
                                       value="price_low"
                                       {{ request('sort') == 'price_low' ? 'checked' : '' }}
                                       onchange="applyFilters()"
                                       class="w-4 h-4 text-primary border-border focus:ring-primary focus:ring-2">
                                <span class="ml-2 text-sm">Price: Low to High</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="sort" 
                                       value="price_high"
                                       {{ request('sort') == 'price_high' ? 'checked' : '' }}
                                       onchange="applyFilters()"
                                       class="w-4 h-4 text-primary border-border focus:ring-primary focus:ring-2">
                                <span class="ml-2 text-sm">Price: High to Low</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="sort" 
                                       value="name"
                                       {{ request('sort') == 'name' ? 'checked' : '' }}
                                       onchange="applyFilters()"
                                       class="w-4 h-4 text-primary border-border focus:ring-primary focus:ring-2">
                                <span class="ml-2 text-sm">Name A-Z</span>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if(request('min_price') || request('max_price') || request('sort'))
                    <button onclick="clearFilters()" class="btn btn-outline w-full">
                        <i class="fas fa-times mr-2"></i>
                        Clear Filters
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php $wishlist = auth()->check() ? auth()->user()->wishlist->pluck('id')->toArray() : []; @endphp
                    @foreach($products as $product)
                        @component('components.product-card', ['product' => $product])
                        @endcomponent
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-muted-foreground"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">No Products Found</h3>
                    <p class="text-muted mb-6">No products available in this category with the current filters.</p>
                    <button onclick="clearFilters()" class="btn btn-primary">
                        <i class="fas fa-refresh mr-2"></i>
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Related Categories -->
    <div class="mt-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold">Other Categories</h2>
            <p class="text-muted">Explore more categories</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($otherCategories ?? [] as $otherCategory)
            <a href="{{ route('categories.show', $otherCategory) }}" class="group">
                <div class="card hover:shadow-lg transition-all duration-300 hover:scale-105 h-full">
                    <div class="card-content p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                            <i class="fas fa-tshirt text-xl text-primary"></i>
                        </div>
                        
                        <h3 class="font-semibold text-lg mb-2 group-hover:text-primary transition-colors">
                            {{ $otherCategory->name }}
                        </h3>
                        
                        <p class="text-sm text-muted mb-4 line-clamp-2">
                            {{ Str::limit($otherCategory->description, 60) }}
                        </p>
                        
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-sm font-medium text-primary">
                                {{ $otherCategory->products_count }} products
                            </span>
                            <i class="fas fa-arrow-right text-sm text-muted group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Add price filters
    const minPrice = document.querySelector('input[name="min_price"]').value;
    const maxPrice = document.querySelector('input[name="max_price"]').value;

    if (minPrice) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'min_price';
        input.value = minPrice;
        form.appendChild(input);
    }

    if (maxPrice) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'max_price';
        input.value = maxPrice;
        form.appendChild(input);
    }

    // Add sort filter
    const sortRadio = document.querySelector('input[name="sort"]:checked');
    if (sortRadio) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sort';
        input.value = sortRadio.value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function clearFilters() {
    window.location.href = window.location.pathname;
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-square {
    aspect-ratio: 1 / 1;
}
</style>
@endsection 