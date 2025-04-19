@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64">
            <div class="flex flex-col h-0 flex-1 bg-gray-800">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <span class="text-white text-xl font-bold">PrintMarket</span>
                    </div>
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <a href="{{ route('vendor.dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-300"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('vendor.services') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-print mr-3 text-gray-400"></i>
                            Services
                        </a>
                        <a href="{{ route('vendor.quotes') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-file-invoice-dollar mr-3 text-gray-400"></i>
                            Quote Requests
                        </a>
                        <a href="{{ route('vendor.orders') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-shopping-cart mr-3 text-gray-400"></i>
                            Orders
                        </a>
                        <a href="{{ route('vendor.reviews') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-star mr-3 text-gray-400"></i>
                            Reviews
                        </a>
                        <a href="{{ route('vendor.subscription') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-credit-card mr-3 text-gray-400"></i>
                            Subscription
                        </a>
                        <a href="{{ route('vendor.profile') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-user-cog mr-3 text-gray-400"></i>
                            Profile
                        </a>
                        <a href="{{ route('vendor.settings') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-cog mr-3 text-gray-400"></i>
                            Settings
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex bg-gray-700 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div>
                                @if(auth()->user()->profile_photo_path)
                                    <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium text-gray-300 hover:text-white">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="md:hidden fixed inset-0 flex z-40 lg:hidden" role="dialog" aria-modal="true" id="mobile-menu" style="display: none;">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" id="close-mobile-menu">
                    <span class="sr-only">Close sidebar</span>
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <span class="text-white text-xl font-bold">PrintMarket</span>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <a href="{{ route('vendor.dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-300"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('vendor.services') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-print mr-3 text-gray-400"></i>
                        Services
                    </a>
                    <a href="{{ route('vendor.quotes') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-file-invoice-dollar mr-3 text-gray-400"></i>
                        Quote Requests
                    </a>
                    <a href="{{ route('vendor.orders') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-shopping-cart mr-3 text-gray-400"></i>
                        Orders
                    </a>
                    <a href="{{ route('vendor.reviews') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-star mr-3 text-gray-400"></i>
                        Reviews
                    </a>
                    <a href="{{ route('vendor.subscription') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-credit-card mr-3 text-gray-400"></i>
                        Subscription
                    </a>
                    <a href="{{ route('vendor.profile') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-user-cog mr-3 text-gray-400"></i>
                        Profile
                    </a>
                    <a href="{{ route('vendor.settings') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                        Settings
                    </a>
                </nav>
            </div>
            <div class="flex-shrink-0 flex bg-gray-700 p-4">
                <div class="flex-shrink-0 w-full group block">
                    <div class="flex items-center">
                        <div>
                            @if(auth()->user()->profile_photo_path)
                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-base font-medium text-white">{{ auth()->user()->name }}</p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-gray-300 hover:text-white">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
            <button type="button" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" id="open-mobile-menu">
                <span class="sr-only">Open sidebar</span>
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
        
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <h1 class="text-2xl font-semibold text-gray-900">Vendor Dashboard</h1>
                </div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <!-- Dashboard content -->
                    <div class="py-4">
                        <!-- Stats -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                            <i class="fas fa-file-invoice-dollar text-blue-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Quotes</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ $pendingQuotes }}</div>
                                                    <div class="text-sm text-gray-500">{{ $newQuotesToday }} new today</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('vendor.quotes') }}" class="font-medium text-blue-600 hover:text-blue-500">View all<span class="sr-only"> pending quotes</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                            <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Active Orders</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ $activeOrders }}</div>
                                                    <div class="text-sm text-gray-500">{{ $ordersNeedingAttention }} need attention</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('vendor.orders') }}" class="font-medium text-blue-600 hover:text-blue-500">View all<span class="sr-only"> active orders</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                            <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Monthly Revenue</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">${{ number_format($monthlyRevenue, 2) }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        @if($revenueChange > 0)
                                                            <span class="text-green-500">+{{ $revenueChange }}%</span>
                                                        @elseif($revenueChange < 0)
                                                            <span class="text-red-500">{{ $revenueChange }}%</span>
                                                        @else
                                                            <span>0%</span>
                                                        @endif
                                                        from last month
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('vendor.reports') }}" class="font-medium text-blue-600 hover:text-blue-500">View reports<span class="sr-only"> for revenue</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                            <i class="fas fa-star text-purple-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Average Rating</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ number_format($averageRating, 1) }} ⭐</div>
                                                    <div class="text-sm text-gray-500">Based on {{ $reviewsCount }} reviews</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('vendor.reviews') }}" class="font-medium text-blue-600 hover:text-blue-500">View all<span class="sr-only"> reviews</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subscription Status -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500 mb-8">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Subscription Status: {{ $subscription->plan->name }}</h3>
                                <div class="mt-2 max-w-xl text-sm text-gray-500">
                                    <p>Your {{ strtolower($subscription->plan->name) }} subscription expires in {{ $daysUntilExpiration }} days</p>
                                </div>
                                <div class="mt-3">
                                    <div class="bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $subscriptionPercentRemaining }}%"></div>
                                    </div>
                                </div>
                                <div class="mt-3 text-sm">
                                    <span class="font-medium text-gray-900">Quote Requests:</span>
                                    @if($subscription->plan->unlimited_quotes)
                                        <span>Unlimited</span>
                                    @else
                                        <span>{{ $subscription->quotes_used }} / {{ $subscription->plan->monthly_quotes }}</span>
                                    @endif
                                </div>
                                <div class="mt-5">
                                    <a href="{{ route('vendor.subscription') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Renew Subscription
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Latest Updates</h3>
                                </div>
                                <div class="border-t border-gray-200">
                                    <div class="divide-y divide-gray-200">
                                        @forelse($latestActivities as $activity)
                                            <div class="px-4 py-4 sm:px-6">
                                                <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                            </div>
                                        @empty
                                            <div class="px-4 py-4 sm:px-6 text-center">
                                                <p class="text-sm text-gray-500">No recent activity</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('vendor.activities') }}" class="font-medium text-blue-600 hover:text-blue-500">View all activity<span class="sr-only"> updates</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Actions</h3>
                                </div>
                                <div class="border-t border-gray-200">
                                    <div class="divide-y divide-gray-200">
                                        @if($pendingQuotes > 0)
                                            <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                                                <p class="text-sm text-gray-900">{{ $pendingQuotes }} quote requests awaiting response</p>
                                                <a href="{{ route('vendor.quotes') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Respond
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($ordersNeedingProofs > 0)
                                            <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                                                <p class="text-sm text-gray-900">{{ $ordersNeedingProofs }} orders awaiting proof upload</p>
                                                <a href="{{ route('vendor.orders', ['filter' => 'need_proof']) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Upload
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($reviewsNeedingResponse > 0)
                                            <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                                                <p class="text-sm text-gray-900">{{ $reviewsNeedingResponse }} reviews awaiting response</p>
                                                <a href="{{ route('vendor.reviews', ['filter' => 'no_response']) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Respond
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($pendingQuotes == 0 && $ordersNeedingProofs == 0 && $reviewsNeedingResponse == 0)
                                            <div class="px-4 py-4 sm:px-6 text-center">
                                                <p class="text-sm text-gray-500">No pending actions</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Quote Requests -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-md mb-8">
                            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Quote Requests</h3>
                                <a href="{{ route('vendor.quotes') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($recentQuoteRequests as $quote)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        #QR-{{ $quote->id }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $quote->user->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $quote->service->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $quote->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($quote->status == 'pending')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending
                                                            </span>
                                                        @elseif($quote->status == 'quoted')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                Quoted
                                                            </span>
                                                        @elseif($quote->status == 'accepted')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Accepted
                                                            </span>
                                                        @elseif($quote->status == 'rejected')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Rejected
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if($quote->status == 'pending')
                                                            <a href="{{ route('vendor.quotes.respond', $quote) }}" class="text-blue-600 hover:text-blue-900">Respond</a>
                                                        @else
                                                            <a href="{{ route('vendor.quotes.show', $quote) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                        No recent quote requests
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Active Orders -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Active Orders</h3>
                                <a href="{{ route('vendor.orders') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($activeOrders as $order)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        #ORD-{{ $order->id }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $order->user->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $order->service->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $order->delivery_date->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($order->status == 'pending')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending
                                                            </span>
                                                        @elseif($order->status == 'in_production')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                In Production
                                                            </span>
                                                        @elseif($order->status == 'proof_pending')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                                Proof Pending
                                                            </span>
                                                        @elseif($order->status == 'ready_to_ship')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Ready to Ship
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if($order->status == 'proof_pending')
                                                            <a href="{{ route('vendor.orders.upload_proof', $order) }}" class="text-blue-600 hover:text-blue-900">Upload Proof</a>
                                                        @elseif($order->status == 'ready_to_ship')
                                                            <a href="{{ route('vendor.orders.ship', $order) }}" class="text-blue-600 hover:text-blue-900">Ship</a>
                                                        @else
                                                            <a href="{{ route('vendor.orders.update_status', $order) }}" class="text-blue-600 hover:text-blue-900">Update</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                        No active orders
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mobile menu functionality
    const openMobileMenuButton = document.getElementById('open-mobile-menu');
    const closeMobileMenuButton = document.getElementById('close-mobile-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (openMobileMenuButton && closeMobileMenuButton && mobileMenu) {
        openMobileMenuButton.addEventListener('click', () => {
            mobileMenu.style.display = 'flex';
        });
        
        closeMobileMenuButton.addEventListener('click', () => {
            mobileMenu.style.display = 'none';
        });
    }
</script>
@endpush
