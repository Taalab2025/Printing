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
                        <a href="{{ route('vendors') }}" class="text-gray-700 hover:text-blue-600">Vendors</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                        <span class="text-gray-500">{{ $vendor->name ?? 'Vendor Details' }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if($vendor)
            <!-- Vendor Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="md:flex">
                    <div class="md:flex-shrink-0">
                        @if($vendor->logo)
                            <img class="h-64 w-full object-cover md:w-64" src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}">
                        @else
                            <div class="h-64 w-full md:w-64 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-store text-5xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-grow">
                        <div class="flex flex-wrap items-start justify-between">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $vendor->name }}</h1>
                            @if(isset($vendor->rating))
                                <div class="flex items-center bg-blue-100 px-3 py-1 rounded-full">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-medium">{{ number_format($vendor->rating, 1) }}</span>
                                    @if(isset($vendor->reviews_count) || (isset($vendor->reviews) && $vendor->reviews->count()))
                                        <span class="text-sm text-gray-600 ml-1">
                                            ({{ $vendor->reviews_count ?? ($vendor->reviews ? $vendor->reviews->count() : 0) }} reviews)
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 text-gray-600">
                            {{ $vendor->description ?? 'Professional printing services provider.' }}
                        </div>
                        
                        <div class="mt-4 flex flex-wrap gap-4">
                            @if($vendor->website)
                                <a href="{{ $vendor->website }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-globe mr-2"></i>
                                    <span>Visit Website</span>
                                </a>
                            @endif
                            
                            @if($vendor->phone)
                                <a href="tel:{{ $vendor->phone }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-phone mr-2"></i>
                                    <span>{{ $vendor->phone }}</span>
                                </a>
                            @endif
                            
                            @if($vendor->email)
                                <a href="mailto:{{ $vendor->email }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <span>{{ $vendor->email }}</span>
                                </a>
                            @endif
                        </div>
                        
                        @if($vendor->location)
                            <div class="mt-4 flex items-center text-gray-500">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $vendor->location }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Vendor Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <a href="#services" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Services
                        </a>
                        <a href="#reviews" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Reviews
                        </a>
                        <a href="#about" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            About
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Services Section -->
            <div id="services" class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Available Services</h2>
                
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
                        <p class="text-gray-500 mt-2">This vendor hasn't added any services yet.</p>
                        <a href="{{ route('vendors') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Browse Other Vendors
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Reviews Section -->
            <div id="reviews" class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Customer Reviews</h2>
                    <a href="{{ route('vendors.reviews', $vendor->id) }}" class="text-blue-600 hover:text-blue-800">
                        View All Reviews
                    </a>
                </div>
                
                @if(isset($reviews) && count($reviews) > 0)
                    <div class="space-y-6">
                        @foreach($reviews->take(3) as $review)
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-yellow-400"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <h3 class="font-semibold">{{ $review->title ?? 'Review' }}</h3>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ isset($review->created_at) ? $review->created_at->format('M d, Y') : '' }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-4">{{ $review->content }}</p>
                                
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    <span>{{ $review->user->name ?? 'Anonymous' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(isset($reviews) && count($reviews) > 3)
                        <div class="mt-6 text-center">
                            <a href="{{ route('vendors.reviews', $vendor->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                See All {{ count($reviews) }} Reviews
                            </a>
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <i class="fas fa-comments text-4xl text-gray-400 mb-3"></i>
                        <h3 class="text-xl font-medium text-gray-700">No reviews yet</h3>
                        <p class="text-gray-500 mt-2">Be the first to review this vendor's services.</p>
                    </div>
                @endif
                
                @auth
                    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold mb-4">Write a Review</h3>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="rating">
                                    Rating
                                </label>
                                <div class="flex items-center">
                                    <div class="flex space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" class="text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none rating-star" data-rating="{{ $i }}">
                                                ★
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating" value="5">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                    Title
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" name="title" type="text" placeholder="Summarize your experience">
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                                    Review
                                </label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="content" name="content" rows="4" placeholder="Share your experience with this vendor"></textarea>
                            </div>
                            
                            <div class="flex items-center justify-end">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mt-8 bg-white rounded-lg shadow-md p-6 text-center">
                        <p class="text-gray-600 mb-4">You need to be logged in to leave a review.</p>
                        <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Log In to Write a Review
                        </a>
                    </div>
                @endauth
            </div>
            
            <!-- About Section -->
            <div id="about" class="mb-12">
                <h2 class="text-2xl font-bold mb-6">About {{ $vendor->name }}</h2>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="prose max-w-none">
                        @if($vendor->about)
                            {!! $vendor->about !!}
                        @else
                            <p>{{ $vendor->description ?? 'Information about this vendor is not available at the moment.' }}</p>
                        @endif
                    </div>
                    
                    @if($vendor->specialties)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">Specialties</h3>
                            <div class="flex flex-wrap gap-2">
                                @if(is_array($vendor->specialties))
                                    @foreach($vendor->specialties as $specialty)
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full">{{ $specialty }}</span>
                                    @endforeach
                                @elseif(is_string($vendor->specialties))
                                    @foreach(explode(',', $vendor->specialties) as $specialty)
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full">{{ trim($specialty) }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Business Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($vendor->established)
                                <div>
                                    <span class="text-gray-500">Established:</span>
                                    <span class="ml-2">{{ $vendor->established }}</span>
                                </div>
                            @endif
                            
                            @if($vendor->business_hours)
                                <div>
                                    <span class="text-gray-500">Business Hours:</span>
                                    <span class="ml-2">{{ $vendor->business_hours }}</span>
                                </div>
                            @endif
                            
                            @if($vendor->location)
                                <div>
                                    <span class="text-gray-500">Location:</span>
                                    <span class="ml-2">{{ $vendor->location }}</span>
                                </div>
                            @endif
                            
                            @if($vendor->service_area)
                                <div>
                                    <span class="text-gray-500">Service Area:</span>
                                    <span class="ml-2">{{ $vendor->service_area }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Section -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Contact {{ $vendor->name }}</h2>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="md:flex">
                        <div class="md:w-1/2 mb-6 md:mb-0 md:pr-6">
                            <h3 class="text-lg font-semibold mb-4">Send a Message</h3>
                            <form action="{{ route('support.contact') }}" method="POST">
                                @csrf
                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                        Your Name
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Your name" value="{{ auth()->user()->name ?? '' }}">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                        Your Email
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="Your email" value="{{ auth()->user()->email ?? '' }}">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subject">
                                        Subject
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="subject" name="subject" type="text" placeholder="Subject">
                                </div>
                                
                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                                        Message
                                    </label>
                                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="message" name="message" rows="4" placeholder="Your message"></textarea>
                                </div>
                                
                                <div class="flex items-center justify-end">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="md:w-1/2 md:pl-6 border-t md:border-t-0 md:border-l border-gray-200 pt-6 md:pt-0 md:pl-6">
                            <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                            
                            <div class="space-y-4">
                                @if($vendor->phone)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 text-blue-600">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="ml-3 text-gray-700">
                                            <p class="text-sm font-medium">Phone</p>
                                            <p>{{ $vendor->phone }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($vendor->email)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 text-blue-600">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="ml-3 text-gray-700">
                                            <p class="text-sm font-medium">Email</p>
                                            <p>{{ $vendor->email }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($vendor->website)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 text-blue-600">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div class="ml-3 text-gray-700">
                                            <p class="text-sm font-medium">Website</p>
                                            <a href="{{ $vendor->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                                                {{ $vendor->website }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($vendor->location)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 text-blue-600">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="ml-3 text-gray-700">
                                            <p class="text-sm font-medium">Address</p>
                                            <p>{{ $vendor->location }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($vendor->business_hours)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 text-blue-600">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="ml-3 text-gray-700">
                                            <p class="text-sm font-medium">Business Hours</p>
                                            <p>{{ $vendor->business_hours }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            @if($vendor->social_media)
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Follow Us</h4>
                                    <div class="flex space-x-4">
                                        @if(isset($vendor->social_media['facebook']))
                                            <a href="{{ $vendor->social_media['facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800">
                                                <i class="fab fa-facebook-f text-xl"></i>
                                            </a>
                                        @endif
                                        
                                        @if(isset($vendor->social_media['twitter']))
                                            <a href="{{ $vendor->social_media['twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-600">
                                                <i class="fab fa-twitter text-xl"></i>
                                            </a>
                                        @endif
                                        
                                        @if(isset($vendor->social_media['instagram']))
                                            <a href="{{ $vendor->social_media['instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-pink-600 hover:text-pink-800">
                                                <i class="fab fa-instagram text-xl"></i>
                                            </a>
                                        @endif
                                        
                                        @if(isset($vendor->social_media['linkedin']))
                                            <a href="{{ $vendor->social_media['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:text-blue-900">
                                                <i class="fab fa-linkedin-in text-xl"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Request Quote CTA -->
            <div class="bg-blue-600 text-white rounded-lg shadow-md p-6">
                <div class="md:flex items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-bold mb-2">Ready to Get Started?</h2>
                        <p class="text-blue-100">Request a custom quote for your printing project from {{ $vendor->name }}.</p>
                    </div>
                    <a href="{{ route('quotes.create', ['vendor_id' => $vendor->id]) }}" class="inline-block px-6 py-3 bg-white text-blue-600 font-medium rounded-md hover:bg-blue-50 transition-colors">
                        Request a Quote
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <i class="fas fa-exclamation-circle text-4xl text-red-400 mb-3"></i>
                <h3 class="text-xl font-medium text-gray-700">Vendor Not Found</h3>
                <p class="text-gray-500 mt-2">The vendor you're looking for doesn't exist or has been removed.</p>
                <a href="{{ route('vendors') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Browse All Vendors
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Rating star functionality
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;
                
                // Update star colors
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('text-yellow-400');
                        s.classList.remove('text-gray-300');
                    } else {
                        s.classList.add('text-gray-300');
                        s.classList.remove('text-yellow-400');
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
