@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl">
                    Find the Perfect Printing Service for Your Needs
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl">
                    Compare quotes from top printing vendors in Egypt
                </p>
                <div class="mt-10 max-w-xl mx-auto">
                    <div class="flex rounded-md shadow-sm">
                        <input type="text" name="search" id="search" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-l-md sm:text-lg border-gray-300" placeholder="What do you need to print?">
                        <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-r-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                Browse Categories
            </h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="group">
                    <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas {{ $category->icon }} text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 text-center">{{ $category->name }}</h3>
                        <p class="mt-1 text-sm text-gray-500 text-center">{{ $category->description }}</p>
                    </div>
                </a>
                @endforeach
                <a href="{{ route('categories') }}" class="group">
                    <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-th text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 text-center">View All</h3>
                        <p class="mt-1 text-sm text-gray-500 text-center">Explore all categories</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Vendors Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                Featured Vendors
            </h2>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredVendors as $vendor)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full overflow-hidden">
                                @if($vendor->logo)
                                    <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                        <i class="fas fa-store text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $vendor->name }}</h3>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $vendor->rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $vendor->rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-1 text-gray-500">({{ $vendor->reviews_count }})</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600">{{ Str::limit($vendor->description, 100) }}</p>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                            <span>{{ $vendor->city }}, {{ $vendor->country }}</span>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('vendors.show', $vendor) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('vendors') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View All Vendors
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                How It Works
            </h2>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-xl">1</span>
                    </div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Request Quotes</h3>
                    <p class="mt-2 text-base text-gray-500">Submit your printing requirements and receive quotes from multiple vendors</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-xl">2</span>
                    </div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Compare Offers</h3>
                    <p class="mt-2 text-base text-gray-500">Compare prices, delivery times, and vendor ratings to find the best match</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-xl">3</span>
                    </div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Place Order</h3>
                    <p class="mt-2 text-base text-gray-500">Accept the best quote and place your order securely through our platform</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-xl">4</span>
                    </div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Receive & Review</h3>
                    <p class="mt-2 text-base text-gray-500">Get your printing delivered and leave a review for the vendor</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                Customer Testimonials
            </h2>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex text-yellow-400 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial->rating)
                                <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= $testimonial->rating)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-gray-600 italic mb-6">{{ $testimonial->content }}</p>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full overflow-hidden">
                            @if($testimonial->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $testimonial->user->profile_photo_path) }}" alt="{{ $testimonial->user->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">{{ $testimonial->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $testimonial->user->role }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                Are You a Printing Service Provider?
            </h2>
            <p class="mt-4 text-xl">
                Join our marketplace to reach more customers and grow your business
            </p>
            <div class="mt-8">
                <a href="{{ route('vendor.register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-white">
                    Register as a Vendor
                </a>
            </div>
        </div>
    </section>
@endsection
