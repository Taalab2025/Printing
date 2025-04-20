@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
            <div class="md:flex">
                <div class="md:w-1/2 p-8 md:p-12">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Contact Us</h1>
                    <p class="text-gray-600 mb-6">We're here to help with any questions about our printing marketplace</p>
                    <p class="text-gray-600 mb-6">
                        Whether you're a customer looking for the perfect printing service or a vendor interested in joining our platform,
                        our team is ready to assist you. Fill out the form, and we'll get back to you as soon as possible.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Email</h4>
                                <p class="text-gray-600">support@printingmarketplace.com</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Phone</h4>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Address</h4>
                                <p class="text-gray-600">
                                    123 Printing Avenue<br>
                                    Suite 456<br>
                                    New York, NY 10001
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 bg-gray-50 p-8 md:p-12">
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                            <input type="text" name="name" id="name" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                            <input type="email" name="email" id="email" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input type="text" name="subject" id="subject" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('subject')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea name="message" id="message" rows="5" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                            @error('message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="inquiry_type" class="block text-sm font-medium text-gray-700 mb-1">Inquiry Type</label>
                            <select name="inquiry_type" id="inquiry_type" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="general">General Inquiry</option>
                                <option value="customer_support">Customer Support</option>
                                <option value="vendor_inquiry">Vendor Inquiry</option>
                                <option value="partnership">Partnership Opportunity</option>
                                <option value="feedback">Feedback</option>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Frequently Asked Questions</h2>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-medium text-gray-800 focus:outline-none">
                            <span>How do I place an order with a printing vendor?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="mt-2">
                            <p class="text-gray-600">
                                You can place an order by browsing our marketplace, selecting a vendor, and choosing the service you need. 
                                You can either order directly through the platform or request a custom quote for more specific requirements.
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-medium text-gray-800 focus:outline-none">
                            <span>How do I become a vendor on your platform?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="mt-2">
                            <p class="text-gray-600">
                                To become a vendor, click on the "Become a Vendor" button and complete the registration process. 
                                You'll need to provide information about your business, services, and pricing. Our team will review 
                                your application and get back to you within 2-3 business days.
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-medium text-gray-800 focus:outline-none">
                            <span>What payment methods do you accept?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="mt-2">
                            <p class="text-gray-600">
                                We accept major credit cards (Visa, Mastercard, American Express), PayPal, and bank transfers. 
                                Payment methods may vary by vendor, so please check the vendor's profile for specific payment options.
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-medium text-gray-800 focus:outline-none">
                            <span>How long does shipping take?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="mt-2">
                            <p class="text-gray-600">
                                Shipping times vary depending on the vendor, the type of printing service, and your location. 
                                Most vendors provide estimated delivery times on their service listings. You can also contact 
                                the vendor directly for more specific shipping information.
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <button class="flex justify-between items-center w-full text-left font-medium text-gray-800 focus:outline-none">
                            <span>What if I'm not satisfied with my order?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="mt-2">
                            <p class="text-gray-600">
                                If you're not satisfied with your order, please contact the vendor directly first to resolve the issue. 
                                If you're unable to reach a resolution, our customer support team is here to help. We have a satisfaction 
                                guarantee and will work with you and the vendor to find a solution.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-6">
                <a href="{{ url('/faqs') }}" class="inline-block px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition">
                    View All FAQs
                </a>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mb-12">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    <!-- Replace with actual map integration -->
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500">Interactive map would be displayed here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-blue-600 rounded-lg shadow-md p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Need Help with a Printing Project?</h2>
            <p class="mb-6 max-w-3xl mx-auto">
                Our team of printing experts is ready to help you find the perfect solution for your needs.
                Get started today and experience the convenience of our printing marketplace.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ url('/categories') }}" class="inline-block px-6 py-3 bg-white text-blue-600 font-medium rounded-md hover:bg-gray-100 transition">
                    Browse Services
                </a>
                <a href="{{ route('quotes.create') }}" class="inline-block px-6 py-3 bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 transition">
                    Request a Quote
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
