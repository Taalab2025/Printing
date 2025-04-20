@extends('layouts.app')

@section('title', 'Browse Services')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="{{ url('/') }}" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-home"></i>
                        <span class="sr-only">Home</span>
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">Services</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Filters</h2>
                
                <form action="{{ route('services') }}" method="GET">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Categories</h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input id="category-{{ $category->id }}" name="categories[]" value="{{ $category->id }}" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                                    <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Price Range</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="min_price" class="sr-only">Min Price</label>
                                <input type="number" id="min_price" name="min_price" value="{{ $minPrice ?? '' }}" min="0" placeholder="Min" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="max_price" class="sr-only">Max Price</label>
                                <input type="number" id="max_price" name="max_price" value="{{ $maxPrice ?? '' }}" min="0" placeholder="Max" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vendor Rating -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Vendor Rating</h3>
                        <div class="space-y-2">
                            @foreach([5, 4, 3, 2, 1] as $rating)
                                <div class="flex items-center">
                                    <input id="rating-{{ $rating }}" name="ratings[]" value="{{ $rating }}" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array($rating, $selectedRatings ?? []) ? 'checked' : '' }}>
                                    <label for="rating-{{ $rating }}" class="ml-2 text-sm text-gray-700 flex items-center">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-1">& Up</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Location</h3>
                        <select id="location" name="location" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Locations</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" {{ ($selectedLocation ?? '') == $location ? 'selected' : '' }}>{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Apply Filters Button -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filters
                        </button>
                        <a href="{{ route('services') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Clear All
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Services Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Browse Services</h1>
                
                <div class="flex items-center space-x-4">
                    <!-- Sort Dropdown -->
                    <div>
                        <label for="sort" class="sr-only">Sort</label>
                        <select id="sort" name="sort" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" onchange="window.location.href = this.value">
                            <option value="{{ route('services', array_merge(request()->query(), ['sort' => 'relevance'])) }}" {{ ($sort ?? 'relevance') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                            <option value="{{ route('services', array_merge(request()->query(), ['sort' => 'price_low'])) }}" {{ ($sort ?? '') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ route('services', array_merge(request()->query(), ['sort' => 'price_high'])) }}" {{ ($sort ?? '') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ route('services', array_merge(request()->query(), ['sort' => 'rating'])) }}" {{ ($sort ?? '') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="{{ route('services', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>Newest</option>
                        </select>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 rounded-md {{ ($view ?? 'grid') == 'grid' ? 'bg-gray-200 text-gray-800' : 'text-gray-500 hover:text-gray-700' }}" onclick="window.location.href = '{{ route('services', array_merge(request()->query(), ['view' => 'grid'])) }}'">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="p-2 rounded-md {{ ($view ?? 'grid') == 'list' ? 'bg-gray-200 text-gray-800' : 'text-gray-500 hover:text-gray-700' }}" onclick="window.location.href = '{{ route('services', array_merge(request()->query(), ['view' => 'list'])) }}'">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Search Results Info -->
            <div class="bg-gray-100 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $services->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $services->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $services->total() }}</span> services
                    </p>
                    
                    @if(!empty($selectedCategories) || !empty($selectedRatings) || !empty($selectedLocation) || !empty($minPrice) || !empty($maxPrice))
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 mr-2">Active Filters:</span>
                            <div class="flex flex-wrap gap-2">
                                @if(!empty($selectedCategories))
                                    @foreach($selectedCategories as $categoryId)
                                        @php $category = $categories->firstWhere('id', $categoryId); @endphp
                                        @if($category)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $category->name }}
                                                <a href="{{ route('services', array_merge(request()->query(), ['categories' => array_diff($selectedCategories, [$categoryId])])) }}" class="ml-1 text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                    @endforeach
                                @endif
                                
                                @if(!empty($minPrice) || !empty($maxPrice))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Price: {{ !empty($minPrice) ? '$'.$minPrice : '$0' }} - {{ !empty($maxPrice) ? '$'.$maxPrice : 'Any' }}
                                        <a href="{{ route('services', array_merge(request()->query(), ['min_price' => null, 'max_price' => null])) }}" class="ml-1 text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                
                                @if(!empty($selectedRatings))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Rating: {{ min($selectedRatings) }}+ Stars
                                        <a href="{{ route('services', array_merge(request()->query(), ['ratings' => null])) }}" class="ml-1 text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                
                                @if(!empty($selectedLocation))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Location: {{ $selectedLocation }}
                                        <a href="{{ route('services', array_merge(request()->query(), ['location' => null])) }}" class="ml-1 text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($services->isEmpty())
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-search text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search filters or browse all services</p>
                    <a href="{{ route('services') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear All Filters
                    </a>
                </div>
            @else
                @if(($view ?? 'grid') == 'grid')
                    <!-- Grid View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($services as $service)
                            <a href="{{ route('services.show', $service) }}" class="group">
                                <div class="bg-white rounded-lg shadow overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                                    <div class="h-48 bg-gray-200 overflow-hidden">
                                        @if($service->media->count() > 0)
                                            <img src="{{ asset('storage/' . $service->media->first()->path) }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class="fas fa-image text-gray-400 text-5xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $service->name }}</h3>
                                        <div class="flex items-center mb-2">
                                            <div class="flex-shrink-0 h-6 w-6 bg-gray-200 rounded-full overflow-hidden mr-2">
                                                @if($service->vendor->logo)
                                                    <img src="{{ asset('storage/' . $service->vendor->logo) }}" alt="{{ $service->vendor->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                                        <i class="fas fa-store text-xs"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $service->vendor->name }}</span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400 text-sm">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $service->vendor->rating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $service->vendor->rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-1 text-xs text-gray-500">({{ $service->vendor->reviews_count }})</span>
                                        </div>
                                        <p class="text-blue-600 font-bold">From {{ $service->formatted_price }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <!-- List View -->
                    <div class="space-y-4">
                        @foreach($services as $service)
                            <a href="{{ route('services.show', $service) }}" class="block">
                                <div class="bg-white rounded-lg shadow overflow-hidden transition-all duration-300 hover:shadow-md">
                                    <div class="flex flex-col sm:flex-row">
                                        <div class="sm:w-48 h-48 bg-gray-200 flex-shrink-0">
                                            @if($service->media->count() > 0)
                                                <img src="{{ asset('storage/' . $service->media->first()->path) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $service->name }}</h3>
                                                    <div class="flex items-center mb-2">
                                                        <div class="flex-shrink-0 h-6 w-6 bg-gray-200 rounded-full overflow-hidden mr-2">
                                                            @if($service->vendor->logo)
                                                                <img src="{{ asset('storage/' . $service->vendor->logo) }}" alt="{{ $service->vendor->name }}" class="h-full w-full object-cover">
                                                            @else
                                                                <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                                                    <i class="fas fa-store text-xs"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="text-sm text-gray-500">{{ $service->vendor->name }}</span>
                                                    </div>
                                                </div>
                                                <p class="text-blue-600 font-bold">From {{ $service->formatted_price }}</p>
                                            </div>
                                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $service->short_description }}</p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="flex text-yellow-400 text-sm">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $service->vendor->rating)
                                                                <i class="fas fa-star"></i>
                                                            @elseif($i - 0.5 <= $service->vendor->rating)
                                                                <i class="fas fa-star-half-alt"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="ml-1 text-xs text-gray-500">({{ $service->vendor->reviews_count }})</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                                    <span>{{ $service->vendor->city }}, {{ $service->vendor->country }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $services->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
