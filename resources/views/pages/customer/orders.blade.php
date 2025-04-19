@extends('layouts.app')

@section('title', 'Order Management')

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
                    <span class="ml-4 text-sm font-medium text-gray-500">My Orders</span>
                </div>
            </li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>
    
    <!-- Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('orders', ['status' => 'all']) }}" class="border-{{ request('status', 'all') == 'all' ? 'blue-500 text-blue-600' : 'transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    All Orders
                </a>
                <a href="{{ route('orders', ['status' => 'in_progress']) }}" class="border-{{ request('status') == 'in_progress' ? 'blue-500 text-blue-600' : 'transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    In Progress
                </a>
                <a href="{{ route('orders', ['status' => 'completed']) }}" class="border-{{ request('status') == 'completed' ? 'blue-500 text-blue-600' : 'transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Completed
                </a>
                <a href="{{ route('orders', ['status' => 'cancelled']) }}" class="border-{{ request('status') == 'cancelled' ? 'blue-500 text-blue-600' : 'transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Cancelled
                </a>
            </nav>
        </div>
    </div>
    
    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-shopping-cart text-5xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
            <p class="text-gray-500 mb-4">You don't have any {{ request('status') != 'all' ? request('status') . ' ' : '' }}orders yet.</p>
            <a href="{{ route('services') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Browse Services
            </a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($orders as $order)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
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
                                        <h2 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('orders.show', $order) }}" class="hover:underline">Order #{{ $order->id }}</a>
                                        </h2>
                                        <p class="text-sm text-gray-500">{{ $order->service->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
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
                            </div>
                            
                            <div class="mt-4 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <div class="flex items-center text-sm text-gray-500 mr-6">
                                        <i class="fas fa-store text-gray-400 mr-1.5"></i>
                                        <p>{{ $order->vendor->name }}</p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 mr-6">
                                        <i class="fas fa-calendar text-gray-400 mr-1.5"></i>
                                        <p>Ordered on {{ $order->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <i class="fas fa-truck text-gray-400 mr-1.5"></i>
                                        <p>Delivery by {{ $order->delivery_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm font-medium sm:mt-0">
                                    <p class="text-blue-600">{{ $order->formatted_total }}</p>
                                    <a href="{{ route('orders.show', $order) }}" class="ml-4 text-blue-600 hover:text-blue-900">
                                        View Order <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
