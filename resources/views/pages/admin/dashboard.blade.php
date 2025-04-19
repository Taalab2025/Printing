@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64">
            <div class="flex flex-col h-0 flex-1 bg-gray-800">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <span class="text-white text-xl font-bold">PrintMarket Admin</span>
                    </div>
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-300"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-users mr-3 text-gray-400"></i>
                            Users
                        </a>
                        <a href="{{ route('admin.vendors') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-store mr-3 text-gray-400"></i>
                            Vendors
                        </a>
                        <a href="{{ route('admin.categories') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-th-large mr-3 text-gray-400"></i>
                            Categories
                        </a>
                        <a href="{{ route('admin.services') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-print mr-3 text-gray-400"></i>
                            Services
                        </a>
                        <a href="{{ route('admin.orders') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-shopping-cart mr-3 text-gray-400"></i>
                            Orders
                        </a>
                        <a href="{{ route('admin.subscriptions') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-credit-card mr-3 text-gray-400"></i>
                            Subscriptions
                        </a>
                        <a href="{{ route('admin.reviews') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-star mr-3 text-gray-400"></i>
                            Reviews
                        </a>
                        <a href="{{ route('admin.reports') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-chart-bar mr-3 text-gray-400"></i>
                            Reports
                        </a>
                        <a href="{{ route('admin.settings') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
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
                    <span class="text-white text-xl font-bold">PrintMarket Admin</span>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-300"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-users mr-3 text-gray-400"></i>
                        Users
                    </a>
                    <a href="{{ route('admin.vendors') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-store mr-3 text-gray-400"></i>
                        Vendors
                    </a>
                    <a href="{{ route('admin.categories') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-th-large mr-3 text-gray-400"></i>
                        Categories
                    </a>
                    <a href="{{ route('admin.services') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-print mr-3 text-gray-400"></i>
                        Services
                    </a>
                    <a href="{{ route('admin.orders') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-shopping-cart mr-3 text-gray-400"></i>
                        Orders
                    </a>
                    <a href="{{ route('admin.subscriptions') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-credit-card mr-3 text-gray-400"></i>
                        Subscriptions
                    </a>
                    <a href="{{ route('admin.reviews') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-star mr-3 text-gray-400"></i>
                        Reviews
                    </a>
                    <a href="{{ route('admin.reports') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-chart-bar mr-3 text-gray-400"></i>
                        Reports
                    </a>
                    <a href="{{ route('admin.settings') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
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
                    <h1 class="text-2xl font-semibold text-gray-900">Admin Dashboard</h1>
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
                                            <i class="fas fa-users text-blue-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ $totalUsers }}</div>
                                                    <div class="text-sm text-gray-500">{{ $newUsersToday }} new today</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('admin.users') }}" class="font-medium text-blue-600 hover:text-blue-500">View all<span class="sr-only"> users</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                            <i class="fas fa-store text-green-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Active Vendors</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ $activeVendors }}</div>
                                                    <div class="text-sm text-gray-500">{{ $pendingVendors }} pending approval</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('admin.vendors') }}" class="font-medium text-blue-600 hover:text-blue-500">View all<span class="sr-only"> vendors</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                            <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ $totalOrders }}</div>
                                                    <div class="text-sm text-gray-500">{{ $ordersToday }} today</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('admin.orders') }}" class="font-medium text-blue-600 hover:text-blue-500">View all<span class="sr-only"> orders</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
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
                                        <a href="{{ route('admin.reports') }}" class="font-medium text-blue-600 hover:text-blue-500">View reports<span class="sr-only"> for revenue</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pending Actions -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500 mb-8">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Actions</h3>
                                <div class="mt-4 divide-y divide-gray-200">
                                    @if($pendingVendors > 0)
                                        <div class="py-3 flex justify-between items-center">
                                            <p class="text-sm text-gray-900">{{ $pendingVendors }} vendors awaiting approval</p>
                                            <a href="{{ route('admin.vendors', ['filter' => 'pending']) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Review
                                            </a>
                                        </div>
                                    @endif
                                    
                                    @if($pendingReports > 0)
                                        <div class="py-3 flex justify-between items-center">
                                            <p class="text-sm text-gray-900">{{ $pendingReports }} content reports to review</p>
                                            <a href="{{ route('admin.reports.content') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Review
                                            </a>
                                        </div>
                                    @endif
                                    
                                    @if($pendingWithdrawals > 0)
                                        <div class="py-3 flex justify-between items-center">
                                            <p class="text-sm text-gray-900">{{ $pendingWithdrawals }} withdrawal requests pending</p>
                                            <a href="{{ route('admin.finance.withdrawals') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Process
                                            </a>
                                        </div>
                                    @endif
                                    
                                    @if($pendingVendors == 0 && $pendingReports == 0 && $pendingWithdrawals == 0)
                                        <div class="py-3 text-center">
                                            <p class="text-sm text-gray-500">No pending actions</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent User Registrations</h3>
                                </div>
                                <div class="border-t border-gray-200">
                                    <div class="divide-y divide-gray-200">
                                        @forelse($recentUsers as $user)
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 overflow-hidden">
                                                            @if($user->profile_photo_path)
                                                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                                            @else
                                                                <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-700">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $user->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-4 sm:px-6 text-center">
                                                <p class="text-sm text-gray-500">No recent registrations</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('admin.users') }}" class="font-medium text-blue-600 hover:text-blue-500">View all users<span class="sr-only"> registrations</span></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Orders</h3>
                                </div>
                                <div class="border-t border-gray-200">
                                    <div class="divide-y divide-gray-200">
                                        @forelse($recentOrders as $order)
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</div>
                                                        <div class="text-sm text-gray-500">{{ $order->user->name }} - {{ $order->service->name }}</div>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($order->status == 'in_production') bg-blue-100 text-blue-800
                                                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                            @endif
                                                        ">
                                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                        </span>
                                                        <span class="ml-4 text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-4 sm:px-6 text-center">
                                                <p class="text-sm text-gray-500">No recent orders</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a href="{{ route('admin.orders') }}" class="font-medium text-blue-600 hover:text-blue-500">View all orders<span class="sr-only"> history</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Status -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-md mb-8">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">System Status</h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Application Version</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ config('app.version') }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ phpversion() }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Database</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">MySQL {{ DB::select('select version() as version')[0]->version }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Server</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Last Backup</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            @if($lastBackup)
                                                {{ $lastBackup->format('M d, Y H:i') }}
                                                <span class="text-gray-500">({{ $lastBackup->diffForHumans() }})</span>
                                            @else
                                                No backups found
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
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
