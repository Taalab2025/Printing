@extends('layouts.app')

@section('title', 'Order Details')

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
                    <a href="{{ route('orders') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">My Orders</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">Order #{{ $order->id }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'in_production') bg-blue-100 text-blue-800
                @elseif($order->status == 'proof_pending') bg-purple-100 text-purple-800
                @elseif($order->status == 'ready_to_ship') bg-indigo-100 text-indigo-800
                @elseif($order->status == 'shipped') bg-teal-100 text-teal-800
                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                @endif
            ">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Service</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full overflow-hidden">
                                @if($order->service->media->count() > 0)
                                    <img src="{{ asset('storage/' . $order->service->media->first()->path) }}" alt="{{ $order->service->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-print text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h2 class="text-base font-medium text-gray-900">
                                    <a href="{{ route('services.show', $order->service) }}" class="hover:underline">{{ $order->service->name }}</a>
                                </h2>
                                <p class="text-sm text-gray-500">{{ $order->service->short_description }}</p>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full overflow-hidden">
                                @if($order->vendor->logo)
                                    <img src="{{ asset('storage/' . $order->vendor->logo) }}" alt="{{ $order->vendor->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                        <i class="fas fa-store text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h2 class="text-base font-medium text-gray-900">
                                    <a href="{{ route('vendors.show', $order->vendor) }}" class="hover:underline">{{ $order->vendor->name }}</a>
                                </h2>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $order->vendor->rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $order->vendor->rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-1 text-xs text-gray-500">({{ $order->vendor->reviews_count }})</span>
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Order Details</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="border border-gray-200 rounded-md divide-y divide-gray-200">
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Quantity</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->quantity }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Specifications</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->specifications }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Order Date</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Expected Delivery</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->delivery_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <address class="not-italic">
                            {{ $order->shipping_name }}<br>
                            {{ $order->shipping_address_line1 }}<br>
                            @if($order->shipping_address_line2)
                                {{ $order->shipping_address_line2 }}<br>
                            @endif
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                            {{ $order->shipping_country }}
                        </address>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Contact Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <p>{{ $order->contact_name }}</p>
                        <p>{{ $order->contact_email }}</p>
                        <p>{{ $order->contact_phone }}</p>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Order Files</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($order->files->count() > 0)
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach($order->files as $file)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <i class="fas fa-file-alt flex-shrink-0 h-5 w-5 text-gray-400"></i>
                                            <span class="ml-2 flex-1 w-0 truncate">{{ $file->original_name }}</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="{{ route('orders.download', $file) }}" class="font-medium text-blue-600 hover:text-blue-500">
                                                Download
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">No files uploaded</p>
                        @endif
                    </dd>
                </div>
                @if($order->proofs->count() > 0)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Proofs</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach($order->proofs as $proof)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <i class="fas fa-file-image flex-shrink-0 h-5 w-5 text-gray-400"></i>
                                            <span class="ml-2 flex-1 w-0 truncate">Proof #{{ $loop->iteration }} - {{ $proof->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-4">
                                            <a href="{{ route('orders.proof', $proof) }}" class="font-medium text-blue-600 hover:text-blue-500">
                                                View
                                            </a>
                                            @if($proof->status == 'pending')
                                                <form action="{{ route('orders.approve_proof', $proof) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="font-medium text-green-600 hover:text-green-500">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('orders.reject_proof', $proof) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="font-medium text-red-600 hover:text-red-500">
                                                        Request Changes
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-500">
                                                    {{ ucfirst($proof->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Payment Summary</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="border border-gray-200 rounded-md divide-y divide-gray-200">
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Subtotal</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->formatted_subtotal }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Shipping</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->formatted_shipping }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Tax</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->formatted_tax }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm font-medium">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Total</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->formatted_total }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Payment Method</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span>{{ $order->payment_method }}</span>
                                </div>
                            </div>
                            <div class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <span class="flex-1 w-0 truncate">Payment Status</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Order Status Timeline -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">Order Timeline</h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Track the progress of your order</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($order->statusHistory as $history)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                            @if($history->status == 'pending') bg-yellow-500
                                            @elseif($history->status == 'in_production') bg-blue-500
                                            @elseif($history->status == 'proof_pending') bg-purple-500
                                            @elseif($history->status == 'ready_to_ship') bg-indigo-500
                                            @elseif($history->status == 'shipped') bg-teal-500
                                            @elseif($history->status == 'delivered') bg-green-500
                                            @elseif($history->status == 'cancelled') bg-red-500
                                            @endif
                                        ">
                                            @if($history->status == 'pending')
                                                <i class="fas fa-clock text-white"></i>
                                            @elseif($history->status == 'in_production')
                                                <i class="fas fa-print text-white"></i>
                                            @elseif($history->status == 'proof_pending')
                                                <i class="fas fa-file-image text-white"></i>
                                            @elseif($history->status == 'ready_to_ship')
                                                <i class="fas fa-box text-white"></i>
                                            @elseif($history->status == 'shipped')
                                                <i class="fas fa-truck text-white"></i>
                                            @elseif($history->status == 'delivered')
                                                <i class="fas fa-check text-white"></i>
                                            @elseif($history->status == 'cancelled')
                                                <i class="fas fa-times text-white"></i>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $history->status)) }}</p>
                                            @if($history->notes)
                                                <p class="text-sm text-gray-500">{{ $history->notes }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $history->created_at->format('Y-m-d H:i') }}">{{ $history->created_at->format('M d, Y') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
        @if($order->status == 'pending' || $order->status == 'in_production')
            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i> Cancel Order
                </button>
            </form>
        @endif
        
        @if($order->status == 'delivered' && !$order->hasReview)
            <a href="{{ route('reviews.create', $order) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-star mr-2"></i> Leave Review
            </a>
        @endif
        
        <a href="{{ route('orders') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
        
        <a href="{{ route('support.create', ['order_id' => $order->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-question-circle mr-2"></i> Get Support
        </a>
        
        <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-file-invoice mr-2"></i> View Invoice
        </a>
    </div>
</div>
@endsection
