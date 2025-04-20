@extends('layouts.app')

@section('title', 'How It Works')

@section('content')
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
            <div class="md:flex">
                <div class="md:w-1/2 p-8 md:p-12">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">How Our Printing Marketplace Works</h1>
                    <p class="text-gray-600 mb-6">Simple, efficient, and designed with your printing needs in mind</p>
                    <p class="text-gray-600 mb-6">
                        Our printing marketplace connects businesses and individuals with quality printing services through a 
                        streamlined platform. Whether you need business cards, brochures, banners, or custom printing solutions, 
                        our marketplace makes it easy to find, compare, and order from verified printing vendors.
                    </p>
                </div>
                <div class="md:w-1/2 bg-blue-600">
                    <div class="h-64 md:h-full flex items-center justify-center p-8">
                        <img src="https://images.unsplash.com/photo-1588681664899-f142ff2dc9b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Printing Process" class="max-h-full rounded-lg shadow-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- For Customers -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">For Customers</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Browse & Compare</h3>
                    <p class="text-gray-600">
                        Search our marketplace for printing services based on category, location, price, and ratings. 
                        Compare different vendors to find the perfect match for your printing needs.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Order or Request Quote</h3>
                    <p class="text-gray-600">
                        Place an order directly for standard services with fixed pricing, or request a custom quote 
                        for more complex projects with specific requirements.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Receive & Review</h3>
                    <p class="text-gray-600">
                        Track your order through our platform, receive your printed materials, and leave a review 
                        to help other customers and recognize quality vendors.
                    </p>
                </div>
            </div>
        </div>

        <!-- Customer Journey -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Customer Journey</h2>
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-0 md:left-1/2 top-0 bottom-0 w-1 bg-blue-200 transform md:translate-x-0 translate-x-4"></div>
                    
                    <!-- Timeline Items -->
                    <div class="space-y-12">
                        <!-- Item 1 -->
                        <div class="relative flex flex-col md:flex-row items-start">
                            <div class="md:w-1/2 md:pr-8 md:text-right mb-4 md:mb-0">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Create an Account</h3>
                                <p class="text-gray-600">
                                    Sign up for a free account to access all features of our marketplace, including order history, 
                                    saved vendors, and quote requests.
                                </p>
                            </div>
                            <div class="absolute left-0 md:left-1/2 w-8 h-8 rounded-full bg-blue-500 border-4 border-white transform md:translate-x-0 translate-x-0 flex items-center justify-center">
                                <span class="text-white font-bold">1</span>
                            </div>
                            <div class="md:w-1/2 md:pl-8 pl-12">
                                <img src="https://images.unsplash.com/photo-1517292987719-0369a794ec0f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Create Account" class="rounded-lg shadow-md">
                            </div>
                        </div>
                        
                        <!-- Item 2 -->
                        <div class="relative flex flex-col md:flex-row items-start">
                            <div class="md:w-1/2 md:pr-8 md:text-right order-1 md:order-1 pl-12 md:pl-0 mb-4 md:mb-0">
                                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Search Services" class="rounded-lg shadow-md">
                            </div>
                            <div class="absolute left-0 md:left-1/2 w-8 h-8 rounded-full bg-blue-500 border-4 border-white transform md:translate-x-0 translate-x-0 flex items-center justify-center">
                                <span class="text-white font-bold">2</span>
                            </div>
                            <div class="md:w-1/2 md:pl-8 pl-12 order-2 md:order-2">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Search for Services</h3>
                                <p class="text-gray-600">
                                    Browse categories, use filters, or search directly for the printing services you need. 
                                    View detailed vendor profiles, service descriptions, and customer reviews.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Item 3 -->
                        <div class="relative flex flex-col md:flex-row items-start">
                            <div class="md:w-1/2 md:pr-8 md:text-right mb-4 md:mb-0">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Request Quotes</h3>
                                <p class="text-gray-600">
                                    For custom projects, submit a quote request with your specifications. Receive and compare 
                                    quotes from multiple vendors to find the best value.
                                </p>
                            </div>
                            <div class="absolute left-0 md:left-1/2 w-8 h-8 rounded-full bg-blue-500 border-4 border-white transform md:translate-x-0 translate-x-0 flex items-center justify-center">
                                <span class="text-white font-bold">3</span>
                            </div>
                            <div class="md:w-1/2 md:pl-8 pl-12">
                                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Request Quotes" class="rounded-lg shadow-md">
                            </div>
                        </div>
                        
                        <!-- Item 4 -->
                        <div class="relative flex flex-col md:flex-row items-start">
                            <div class="md:w-1/2 md:pr-8 md:text-right order-1 md:order-1 pl-12 md:pl-0 mb-4 md:mb-0">
                                <img src="https://images.unsplash.com/photo-1556741533-6e6a62bd8b49?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Place Order" class="rounded-lg shadow-md">
                            </div>
                            <div class="absolute left-0 md:left-1/2 w-8 h-8 rounded-full bg-blue-500 border-4 border-white transform md:translate-x-0 translate-x-0 flex items-center justify-center">
                                <span class="text-white font-bold">4</span>
                            </div>
                            <div class="md:w-1/2 md:pl-8 pl-12 order-2 md:order-2">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Place Your Order</h3>
                                <p class="text-gray-600">
                                    Complete your order with secure payment options. Upload your design files or work with 
                                    the vendor on design requirements.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Item 5 -->
                        <div class="relative flex flex-col md:flex-row items-start">
                            <div class="md:w-1/2 md:pr-8 md:text-right mb-4 md:mb-0">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Track & Receive</h3>
                                <p class="text-gray-600">
                                    Monitor your order status through our platform. Receive notifications about production 
                                    and shipping. Get your printed materials delivered to your door.
                                </p>
                            </div>
                            <div class="absolute left-0 md:left-1/2 w-8 h-8 rounded-full bg-blue-500 border-4 border-white transform md:translate-x-0 translate-x-0 flex items-center justify-center">
                                <span class="text-white font-bold">5</span>
                            </div>
                            <div class="md:w-1/2 md:pl-8 pl-12">
                                <img src="https://images.unsplash.com/photo-1586769852836-bc069f19e1b6?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Track Order" class="rounded-lg shadow-md">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Vendors -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">For Vendors</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-green-600 text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Join Our Platform</h3>
                    <p class="text-gray-600">
                        Register as a vendor, complete your profile with business details, service offerings, 
                        and pricing. Our team will review and approve your application.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-green-600 text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Manage Orders & Quotes</h3>
                    <p class="text-gray-600">
                        Receive orders and quote requests through our platform. Respond to customers, 
                        manage production, and track order fulfillment all in one place.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-green-600 text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Grow Your Business</h3>
                    <p class="text-gray-600">
                        Build your reputation through customer reviews, showcase your portfolio, 
                        and expand your customer base through our marketplace.
                    </p>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Benefits of Our Marketplace</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">For Customers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Access to a wide network of verified printing vendors</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Easy comparison of services, prices, and reviews</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Streamlined ordering process with secure payments</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Custom quote requests for specialized printing needs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Order tracking and history in one convenient location</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Customer support throughout the ordering process</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">For Vendors</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Increased visibility to potential customers</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Streamlined order management and customer communication</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Secure payment processing with reliable payouts</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Tools to showcase your portfolio and services</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Customer reviews to build your reputation</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-600">Analytics and insights to grow your business</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-blue-600 rounded-lg shadow-md p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="mb-6 max-w-3xl mx-auto">
                Join our printing marketplace today and experience the easiest way to find and order quality printing services.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ url('/register') }}" class="inline-block px-6 py-3 bg-white text-blue-600 font-medium rounded-md hover:bg-gray-100 transition">
                    Sign Up as Customer
                </a>
                <a href="{{ url('/vendor/register') }}" class="inline-block px-6 py-3 bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 transition">
                    Become a Vendor
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
