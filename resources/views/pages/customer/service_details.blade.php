@extends('layouts.app')

@section('title', 'Service Details')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
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
                        <a href="{{ route('categories') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Categories</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('categories.show', $service->category) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $service->category->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">{{ $service->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Service Gallery -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="relative h-96">
                    @if($service->media->count() > 0)
                        <img src="{{ asset('storage/' . $service->media->first()->path) }}" alt="{{ $service->name }}" class="w-full h-full object-cover" id="main-image">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-5xl"></i>
                        </div>
                    @endif
                </div>
                
                @if($service->media->count() > 1)
                    <div class="p-4 flex space-x-4 overflow-x-auto">
                        @foreach($service->media as $media)
                            <div class="w-20 h-20 flex-shrink-0 rounded-md overflow-hidden cursor-pointer {{ $loop->first ? 'ring-2 ring-blue-500' : '' }}" 
                                 onclick="document.getElementById('main-image').src='{{ asset('storage/' . $media->path) }}'; 
                                          document.querySelectorAll('.thumbnail').forEach(el => el.classList.remove('ring-2', 'ring-blue-500'));
                                          this.classList.add('ring-2', 'ring-blue-500');"
                                 class="thumbnail">
                                <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Service Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $service->name }}</h1>
                
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full overflow-hidden mr-3">
                        @if($service->vendor->logo)
                            <img src="{{ asset('storage/' . $service->vendor->logo) }}" alt="{{ $service->vendor->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('vendors.show', $service->vendor) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $service->vendor->name }}</a>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400">
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
                            <span class="ml-1 text-gray-500">({{ $service->vendor->reviews_count }})</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-md mb-6">
                    <div class="text-sm text-gray-500">Starting from</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $service->formatted_price }}</div>
                    <div class="text-sm text-gray-500">{{ $service->price_description }}</div>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Description</h2>
                    <div class="prose max-w-none text-gray-600">
                        {!! $service->description !!}
                    </div>
                </div>
                
                <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('quotes.create', ['service' => $service->id]) }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
                        Request a Quote
                    </a>
                    <a href="{{ route('vendors.contact', $service->vendor) }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
                        Contact Vendor
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button active" data-tab="specifications">
                        Specifications
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-tab="reviews">
                        Reviews
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-tab="vendor-info">
                        Vendor Info
                    </button>
                </nav>
            </div>
        </div>
        
        <!-- Tab Content -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-12">
            <!-- Specifications Tab -->
            <div class="p-6 tab-content active" id="specifications-content">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Product Specifications</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($service->specifications as $spec)
                    <div class="flex">
                        <div class="w-40 font-medium text-gray-500">{{ $spec->name }}:</div>
                        <div class="flex-1 text-gray-900">{{ $spec->value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Reviews Tab -->
            <div class="p-6 tab-content hidden" id="reviews-content">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900">Customer Reviews</h2>
                    <span class="text-sm text-gray-500">{{ $service->reviews_count }} reviews</span>
                </div>
                
                @if($service->reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($service->reviews as $review)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex justify-between mb-2">
                                <div class="font-medium text-gray-900">{{ $review->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="flex text-yellow-400 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $review->rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-gray-600 mb-4">{{ $review->content }}</p>
                            
                            @if($review->vendor_response)
                            <div class="bg-gray-50 p-4 rounded-md mt-3">
                                <div class="font-medium text-gray-900 mb-1">Response from {{ $service->vendor->name }}</div>
                                <p class="text-gray-600">{{ $review->vendor_response }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No reviews yet for this service.</p>
                    </div>
                @endif
            </div>
            
            <!-- Vendor Info Tab -->
            <div class="p-6 tab-content hidden" id="vendor-info-content">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full overflow-hidden mr-4">
                        @if($service->vendor->logo)
                            <img src="{{ asset('storage/' . $service->vendor->logo) }}" alt="{{ $service->vendor->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                <i class="fas fa-store text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $service->vendor->name }}</h2>
                        <div class="flex items-center mt-1">
                            <div class="flex text-yellow-400">
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
                            <span class="ml-1 text-gray-500">({{ $service->vendor->reviews_count }})</span>
                        </div>
                    </div>
                </div>
                
                <div class="prose max-w-none text-gray-600 mb-6">
                    {!! $service->vendor->description !!}
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Contact Information</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mr-2"></i>
                                <span>{{ $service->vendor->address }}, {{ $service->vendor->city }}, {{ $service->vendor->country }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-5 mr-2"></i>
                                <span>{{ $service->vendor->phone }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-5 mr-2"></i>
                                <span>{{ $service->vendor->email }}</span>
                            </li>
                            @if($service->vendor->website)
                            <li class="flex items-center">
                                <i class="fas fa-globe text-gray-400 w-5 mr-2"></i>
                                <a href="{{ $service->vendor->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $service->vendor->website }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Business Hours</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between">
                                <span>Monday - Friday</span>
                                <span>{{ $service->vendor->weekday_hours }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Saturday</span>
                                <span>{{ $service->vendor->saturday_hours }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Sunday</span>
                                <span>{{ $service->vendor->sunday_hours }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:space-x-3">
                    <a href="{{ route('vendors.show', $service->vendor) }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full mb-3 sm:mb-0">
                        View All Services
                    </a>
                    <a href="{{ route('vendors.contact', $service->vendor) }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
                        Contact Vendor
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Related Services -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Services</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedServices as $relatedService)
                <a href="{{ route('services.show', $relatedService) }}" class="group">
                    <div class="bg-white rounded-lg shadow overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($relatedService->media->count() > 0)
                                <img src="{{ asset('storage/' . $relatedService->media->first()->path) }}" alt="{{ $relatedService->name }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $relatedService->name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $relatedService->vendor->name }}</p>
                            <p class="text-blue-600 font-bold">From {{ $relatedService->formatted_price }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('border-blue-500', 'text-blue-600'));
            tabButtons.forEach(btn => btn.classList.add('border-transparent', 'text-gray-500'));
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Add active class to clicked button and corresponding content
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');
            
            const tabId = button.getAttribute('data-tab');
            document.getElementById(`${tabId}-content`).classList.remove('hidden');
        });
    });
</script>
@endpush
