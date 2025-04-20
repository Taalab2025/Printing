@extends('layouts.app')

@section('content')
<div class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                        <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-600">Categories</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                        <span class="text-gray-500">{{ $category->name ?? 'Category Details' }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if($category)
            <!-- Category Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="md:flex">
                    <div class="md:flex-shrink-0">
                        @if($category->image)
                            <img class="h-64 w-full object-cover md:w-64" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <div class="h-64 w-full md:w-64 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-print text-5xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            @if($category->icon)
                                <i class="fas fa-{{ $category->icon }} text-blue-600 mr-2"></i>
                            @endif
                            <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                        </div>
                        
                        <div class="mt-4 text-gray-600">
                            {{ $category->description ?? 'Explore our range of ' . $category->name . ' printing services.' }}
                        </div>
                        
                        @if($category->parent)
                            <div class="mt-4">
                                <span class="text-sm text-gray-500">Parent Category:</span>
                                <a href="{{ route('categories.show', $category->parent->slug ?? $category->parent->id) }}" class="ml-1 text-blue-600 hover:underline">
                                    {{ $category->parent->name }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Subcategories Section -->
            @if(isset($category->children) && $category->children->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Subcategories</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child->slug ?? $child->id) }}" class="bg-white rounded-lg shadow p-4 flex items-center hover:shadow-md transition-shadow">
                                @if($child->icon)
                                    <i class="fas fa-{{ $child->icon }} text-blue-600 text-xl mr-3"></i>
                                @else
                                    <i class="fas fa-folder text-blue-600 text-xl mr-3"></i>
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $child->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $child->services_count ?? ($child->services ? $child->services->count() : 0) }} services
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Services Section -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Available Services</h2>
                    
                    <!-- Filter/Sort Options (Optional) -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Sort by:</span>
                        <select class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option>Popularity</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Newest</option>
                        </select>
                    </div>
                </div>
                
                @if(isset($services) && count($services) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($services as $service)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                                <a href="{{ route('services.show', $service->id) }}">
                                    @if(isset($service->media) && count($service->media) > 0)
                                        <img src="{{ asset('storage/' . $service->media[0]->path) }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-print text-4xl text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold mb-2">{{ $service->name }}</h3>
                                            @if($service->price)
                                                <span class="text-green-600 font-medium">${{ number_format($service->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ $service->description }}</p>
                                        
                                        @if(isset($service->vendor))
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-store mr-1"></i>
                                                <span>{{ $service->vendor->name }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-4 flex justify-between items-center">
                                            <div class="flex items-center">
                                                @if(isset($service->rating))
                                                    <div class="flex items-center">
                                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                                        <span>{{ number_format($service->rating, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium leading-none text-blue-600 bg-blue-100 rounded-full">
                                                View Details
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(method_exists($services, 'links') && $services->hasPages())
                        <div class="mt-8">
                            {{ $services->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-3"></i>
                        <h3 class="text-xl font-medium text-gray-700">No services found</h3>
                        <p class="text-gray-500 mt-2">There are currently no services available in this category.</p>
                        <a href="{{ route('categories.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Browse Other Categories
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Related Categories Section -->
            @if(isset($relatedCategories) && count($relatedCategories) > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-4">Related Categories</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($relatedCategories as $relatedCategory)
                            <a href="{{ route('categories.show', $relatedCategory->slug ?? $relatedCategory->id) }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                                <h3 class="font-medium text-gray-900">{{ $relatedCategory->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $relatedCategory->services_count ?? ($relatedCategory->services ? $relatedCategory->services->count() : 0) }} services
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <i class="fas fa-exclamation-circle text-4xl text-red-400 mb-3"></i>
                <h3 class="text-xl font-medium text-gray-700">Category Not Found</h3>
                <p class="text-gray-500 mt-2">The category you're looking for doesn't exist or has been removed.</p>
                <a href="{{ route('categories.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Browse All Categories
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
