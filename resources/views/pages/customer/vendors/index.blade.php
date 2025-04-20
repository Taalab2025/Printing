@extends('layouts.app')

@section('content')
<div class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Print Vendors</h1>
        
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Find the Perfect Printing Partner</h2>
                <p class="text-gray-600 mb-4">Browse our curated list of professional printing vendors. Each vendor has been vetted for quality, reliability, and customer service.</p>
                
                <!-- Search and Filter Section -->
                <div class="mt-4">
                    <form action="{{ route('vendors') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <input type="text" name="search" placeholder="Search vendors..." value="{{ request('search') }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="w-full md:w-auto">
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by Rating</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by Newest</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($vendors as $vendor)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <a href="{{ route('vendors.show', $vendor->id) }}">
                        @if($vendor->logo)
                            <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-store text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-semibold mb-2">{{ $vendor->name }}</h2>
                                @if(isset($vendor->rating))
                                    <div class="flex items-center bg-blue-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="text-sm font-medium">{{ number_format($vendor->rating, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 line-clamp-2 mb-3">{{ $vendor->description ?? 'Professional printing services provider' }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if(isset($vendor->specialties) && is_array($vendor->specialties))
                                    @foreach($vendor->specialties as $specialty)
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">{{ $specialty }}</span>
                                    @endforeach
                                @elseif(isset($vendor->specialties) && is_string($vendor->specialties))
                                    @foreach(explode(',', $vendor->specialties) as $specialty)
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">{{ trim($specialty) }}</span>
                                    @endforeach
                                @endif
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>{{ $vendor->location ?? 'Location not specified' }}</span>
                            </div>
                            
                            @if(isset($vendor->services_count) || (isset($vendor->services) && $vendor->services->count()))
                                <div class="text-sm text-blue-600">
                                    {{ $vendor->services_count ?? ($vendor->services ? $vendor->services->count() : 0) }} services available
                                </div>
                            @endif
                            
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    @if(isset($vendor->established))
                                        Est. {{ $vendor->established }}
                                    @endif
                                </span>
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium leading-none text-blue-600 bg-blue-100 rounded-full">
                                    View Profile
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
                    <i class="fas fa-store-slash text-4xl text-gray-400 mb-3"></i>
                    <h3 class="text-xl font-medium text-gray-700">No vendors found</h3>
                    <p class="text-gray-500 mt-2">Try adjusting your search criteria or check back later for new printing vendors.</p>
                </div>
            @endforelse
        </div>
        
        @if(method_exists($vendors, 'links') && $vendors->hasPages())
            <div class="mt-8">
                {{ $vendors->links() }}
            </div>
        @endif
        
        <!-- Become a Vendor CTA -->
        <div class="mt-12 bg-blue-600 text-white rounded-lg shadow-md p-6">
            <div class="md:flex items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold mb-2">Are You a Printing Professional?</h2>
                    <p class="text-blue-100">Join our marketplace and connect with customers looking for your services.</p>
                </div>
                <a href="{{ route('vendor.register') }}" class="inline-block px-6 py-3 bg-white text-blue-600 font-medium rounded-md hover:bg-blue-50 transition-colors">
                    Become a Vendor
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
