@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
            <div class="p-8 md:p-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h1>
                <p class="text-gray-600 mb-6">Find answers to common questions about our printing marketplace</p>
                
                <!-- Search Box -->
                <div class="max-w-xl">
                    <div class="relative">
                        <input type="text" placeholder="Search for questions..." 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 pl-10">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#general" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">General</a>
                <a href="#customers" class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition">For Customers</a>
                <a href="#vendors" class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition">For Vendors</a>
                <a href="#orders" class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition">Orders & Shipping</a>
                <a href="#payments" class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition">Payments</a>
                <a href="#technical" class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition">Technical</a>
            </div>
        </div>

        <!-- General FAQs -->
        <div id="general" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">General Questions</h2>
            <div class="bg-white rounded-lg shadow-md">
                <div class="divide-y divide-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What is the Printing Services Marketplace?</h3>
                        <p class="text-gray-600">
                            Our Printing Services Marketplace is an online platform that connects businesses and individuals with 
                            professional printing vendors. We make it easy to find, compare, and order printing services for all your needs, 
                            from business cards and brochures to banners, promotional materials, and more.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How does the marketplace work?</h3>
                        <p class="text-gray-600">
                            Our marketplace works in three simple steps: First, browse and compare printing services from various vendors. 
                            Second, place an order directly or request a custom quote for more specific needs. Third, track your order, 
                            receive your printed materials, and leave a review to help other customers.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Is it free to use the marketplace?</h3>
                        <p class="text-gray-600">
                            Yes, it's completely free to browse the marketplace, create an account, and request quotes. You only pay for 
                            the printing services you order. There are no hidden fees or subscription costs for customers.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How do you ensure quality from vendors?</h3>
                        <p class="text-gray-600">
                            We have a thorough vetting process for all vendors on our platform. We verify their business credentials, 
                            review their printing capabilities, and monitor customer feedback. We also have a satisfaction guarantee 
                            to ensure you receive quality service.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Customers FAQs -->
        <div id="customers" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">For Customers</h2>
            <div class="bg-white rounded-lg shadow-md">
                <div class="divide-y divide-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Do I need to create an account to place an order?</h3>
                        <p class="text-gray-600">
                            Yes, you need to create a free account to place orders or request quotes. This allows you to track your orders, 
                            save favorite vendors, and manage your printing projects more efficiently.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How do I find the right printing service for my needs?</h3>
                        <p class="text-gray-600">
                            You can browse services by category, use our search function with filters for location, price, and ratings, 
                            or submit a quote request to receive proposals from multiple vendors. You can also read customer reviews 
                            to help make your decision.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What if I need help with my design?</h3>
                        <p class="text-gray-600">
                            Many vendors on our platform offer design services in addition to printing. You can filter for vendors that 
                            provide design assistance, or mention your design needs when requesting a quote. Some vendors also offer 
                            templates you can use.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Can I request samples before placing a large order?</h3>
                        <p class="text-gray-600">
                            Yes, many vendors offer sample packs or can produce a small test run before a larger order. You can discuss 
                            this directly with the vendor when requesting a quote or contact them through our messaging system.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Vendors FAQs -->
        <div id="vendors" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">For Vendors</h2>
            <div class="bg-white rounded-lg shadow-md">
                <div class="divide-y divide-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How do I become a vendor on the marketplace?</h3>
                        <p class="text-gray-600">
                            To become a vendor, click on the "Become a Vendor" button and complete the registration process. You'll need 
                            to provide information about your business, services, and pricing. Our team will review your application 
                            and get back to you within 2-3 business days.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What are the fees for vendors?</h3>
                        <p class="text-gray-600">
                            We charge a small commission on completed orders, which varies based on your subscription plan. We offer 
                            different plans to suit businesses of all sizes, from Basic to Premium. You can view our pricing details 
                            on the Vendor Registration page.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How do I receive payments for orders?</h3>
                        <p class="text-gray-600">
                            Payments are processed securely through our platform. Once an order is completed and confirmed by the customer, 
                            the payment (minus our commission) is transferred to your account. You can set up direct deposits to your 
                            bank account in your vendor dashboard.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Can I offer discounts or promotions?</h3>
                        <p class="text-gray-600">
                            Yes, you can create special offers, discounts, and promotions through your vendor dashboard. These can be 
                            time-limited deals, volume discounts, or special packages to attract more customers.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders & Shipping FAQs -->
        <div id="orders" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Orders & Shipping</h2>
            <div class="bg-white rounded-lg shadow-md">
                <div class="divide-y divide-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How long will it take to receive my order?</h3>
                        <p class="text-gray-600">
                            Delivery times vary depending on the vendor, the type of printing service, and your location. Most vendors 
                            provide estimated production and shipping times on their service listings. You can also contact the vendor 
                            directly for more specific information.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Can I track my order?</h3>
                        <p class="text-gray-600">
                            Yes, you can track your order status through your account dashboard. Once your order is shipped, most vendors 
                            provide tracking information so you can monitor the delivery progress.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What if my order is damaged during shipping?</h3>
                        <p class="text-gray-600">
                            If your order arrives damaged, please contact the vendor immediately through our platform. Most vendors have 
                            policies to replace damaged items. If you're unable to resolve the issue with the vendor, our customer support 
                            team is available to help.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Can I cancel or modify my order?</h3>
                        <p class="text-gray-600">
                            Order cancellation and modification policies vary by vendor. In general, you can cancel or modify an order 
                            before it enters production. Once production has started, cancellation or changes may not be possible or 
                            may incur additional fees. Contact the vendor as soon as possible if you need to make changes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments FAQs -->
        <div id="payments" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Payments</h2>
            <div class="bg-white rounded-lg shadow-md">
                <div class="divide-y divide-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What payment methods do you accept?</h3>
                        <p class="text-gray-600">
                            We accept major credit cards (Visa, Mastercard, American Express), PayPal, and bank transfers. 
                            Payment methods may vary by vendor, so please check the vendor's profile for specific payment options.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Is my payment information secure?</h3>
                        <p class="text-gray-600">
                            Yes, we use industry-standard encryption and security measures to protect your payment information. 
                            We never store your full credit card details on our servers. All transactions are processed through 
                            secure payment gateways.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">When will I be charged for my order?</h3>
                        <p class="text-gray-600">
                            For standard orders, you'll be charged at the time of purchase. For custom quote requests, payment terms 
                            will be outlined in the quote and may include a deposit upfront with the balance due before shipping.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Do you offer refunds?</h3>
                        <p class="text-gray-600">
                            Refund policies vary by vendor. Most vendors do not offer refunds for correctly produced custom print orders. 
                            However, if there's an error in production or the quality doesn't meet standards, vendors typically offer 
                            reprints or refunds. Check the vendor's terms and conditions for specific refund policies.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical FAQs -->
        <div id="technical" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Technical Questions</h2>
            <div class="bg-white rounded-lg shadow-md">
                <div class="divide-y divide-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What file formats do vendors accept?</h3>
                        <p class="text-gray-600">
                            Most vendors accept common file formats such as PDF, AI, PSD, JPEG, and PNG. The preferred format is 
                            usually PDF with embedded fonts and high resolution. Specific file requirements vary by vendor and 
                            product, so check the vendor's specifications before submitting your files.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">How do I upload my design files?</h3>
                        <p class="text-gray-600">
                            You can upload your design files during the ordering process. Our platform supports files up to 100MB. 
                            For larger files, you may need to use a file transfer service or contact the vendor directly for alternative 
                            upload methods.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">What resolution should my images be?</h3>
                        <p class="text-gray-600">
                            For best print quality, images should be at least 300 DPI (dots per inch) at the final print size. 
                            Lower resolution images may appear pixelated or blurry when printed. Many vendors offer a file check 
                            service to ensure your images will print well.
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Do I need to include bleed in my design?</h3>
                        <p class="text-gray-600">
                            Yes, for most print products, you should include a bleed area (typically 1/8 inch or 3mm) beyond the 
                            trim edge of your design. This ensures there are no white edges if there's slight movement during cutting. 
                            Many vendors provide templates with bleed and safe areas marked.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Still Have Questions -->
        <div class="bg-blue-600 rounded-lg shadow-md p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Still Have Questions?</h2>
            <p class="mb-6 max-w-3xl mx-auto">
                Our support team is here to help. Contact us and we'll get back to you as soon as possible.
            </p>
            <a href="{{ url('/contact') }}" class="inline-block px-6 py-3 bg-white text-blue-600 font-medium rounded-md hover:bg-gray-100 transition">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
