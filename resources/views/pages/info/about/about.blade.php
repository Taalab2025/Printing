@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
            <div class="md:flex">
                <div class="md:w-1/2 p-8 md:p-12">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">About Our Printing Marketplace</h1>
                    <p class="text-gray-600 mb-6">Connecting businesses with quality printing services since 2023</p>
                    <p class="text-gray-600 mb-6">
                        Our printing marketplace is designed to revolutionize how businesses and individuals find and order printing services. 
                        We bring together the best printing vendors from across the country, offering a wide range of services from business cards 
                        and brochures to large format printing, signage, and promotional materials.
                    </p>
                    <div class="flex items-center">
                        <div class="flex -space-x-2 mr-4">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Team member" class="w-10 h-10 rounded-full border-2 border-white">
                            <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Team member" class="w-10 h-10 rounded-full border-2 border-white">
                            <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Team member" class="w-10 h-10 rounded-full border-2 border-white">
                        </div>
                        <p class="text-sm text-gray-500">Join our growing community</p>
                    </div>
                </div>
                <div class="md:w-1/2 bg-blue-600">
                    <div class="h-64 md:h-full flex items-center justify-center p-8">
                        <div class="text-center text-white">
                            <div class="text-5xl font-bold mb-2">1000+</div>
                            <div class="text-xl">Printing projects completed</div>
                            <div class="mt-6 text-5xl font-bold mb-2">200+</div>
                            <div class="text-xl">Verified printing vendors</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Mission -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Our Mission</h2>
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/3 mb-6 md:mb-0 md:pr-8">
                        <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Our Mission" class="rounded-lg shadow-md">
                    </div>
                    <div class="md:w-2/3">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Connecting Quality Printing Services with Customers</h3>
                        <p class="text-gray-600 mb-4">
                            Our mission is to simplify the process of finding and ordering printing services. We believe that every business, 
                            regardless of size, should have access to high-quality printing solutions at competitive prices.
                        </p>
                        <p class="text-gray-600 mb-4">
                            By creating a transparent marketplace where customers can compare services, read reviews, and get quotes from multiple vendors, 
                            we're empowering businesses to make informed decisions about their printing needs.
                        </p>
                        <p class="text-gray-600">
                            For printing vendors, we provide a platform to showcase their services, expand their customer base, and grow their business 
                            without the high costs of traditional marketing and sales channels.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Values -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Our Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-star text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Quality</h3>
                    <p class="text-gray-600">
                        We're committed to connecting customers with printing vendors who deliver exceptional quality. 
                        All vendors on our platform are vetted and reviewed to ensure they meet our high standards.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-handshake text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Trust</h3>
                    <p class="text-gray-600">
                        We believe in building trust through transparency. Our platform provides honest reviews, 
                        clear pricing, and secure transactions to create a trustworthy environment for all users.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-lightbulb text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Innovation</h3>
                    <p class="text-gray-600">
                        We're constantly innovating to improve the printing service experience. From our quote request system 
                        to our vendor management tools, we're using technology to make printing services more accessible.
                    </p>
                </div>
            </div>
        </div>

        <!-- Our Team -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Our Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Doe" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-1">John Doe</h3>
                        <p class="text-blue-600 mb-4">Founder & CEO</p>
                        <p class="text-gray-600 mb-4">
                            With over 15 years in the printing industry, John founded our marketplace to solve the challenges 
                            he experienced firsthand in connecting printing services with customers.
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Jane Smith" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-1">Jane Smith</h3>
                        <p class="text-blue-600 mb-4">CTO</p>
                        <p class="text-gray-600 mb-4">
                            Jane leads our technology team, bringing her expertise in marketplace platforms and e-commerce 
                            to create a seamless experience for both customers and vendors.
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/men/68.jpg" alt="Robert Johnson" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-1">Robert Johnson</h3>
                        <p class="text-blue-600 mb-4">Head of Vendor Relations</p>
                        <p class="text-gray-600 mb-4">
                            Robert works directly with our printing vendors to ensure they have the tools and support 
                            they need to succeed on our platform and deliver exceptional service.
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Sarah Williams" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-1">Sarah Williams</h3>
                        <p class="text-blue-600 mb-4">Customer Success Manager</p>
                        <p class="text-gray-600 mb-4">
                            Sarah and her team ensure that every customer has a positive experience on our platform, 
                            from finding the right vendor to receiving their completed printing order.
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Join Us -->
        <div class="bg-blue-600 rounded-lg shadow-md p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Join Our Printing Marketplace</h2>
            <p class="mb-6 max-w-3xl mx-auto">
                Whether you're looking for high-quality printing services or you're a printing vendor wanting to reach more customers, 
                our marketplace is the perfect platform for you.
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
