@extends('layouts.app')

@section('title', 'Admin - User Management')

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
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-users mr-3 text-gray-300"></i>
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
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-users mr-3 text-gray-300"></i>
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
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-900">User Management</h1>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Add User
                        </a>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <div class="py-4">
                        <!-- Filters and Search -->
                        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                            <div class="md:flex md:items-center md:justify-between">
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-lg leading-6 font-medium text-gray-900">Filters</h2>
                                </div>
                            </div>
                            <form action="{{ route('admin.users') }}" method="GET" class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
                                <div class="sm:col-span-2">
                                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                    <div class="mt-1">
                                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Name, email, or phone">
                                    </div>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                    <div class="mt-1">
                                        <select id="role" name="role" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">All Roles</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1">
                                        <select id="status" name="status" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="sm:col-span-1">
                                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                                    <div class="mt-1">
                                        <select id="sort" name="sort" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="sm:col-span-1 flex items-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-search mr-2"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Users Table -->
                        <div class="flex flex-col">
                            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        User
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Role
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Joined
                                                    </th>
                                                    <th scope="col" class="relative px-6 py-3">
                                                        <span class="sr-only">Actions</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($users as $user)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10">
                                                                    @if($user->profile_photo_path)
                                                                        <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                                                                    @else
                                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                                            <i class="fas fa-user text-gray-400"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        {{ $user->name }}
                                                                    </div>
                                                                    <div class="text-sm text-gray-500">
                                                                        {{ $user->email }}
                                                                    </div>
                                                                    @if($user->phone)
                                                                        <div class="text-sm text-gray-500">
                                                                            {{ $user->phone }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @foreach($user->roles as $role)
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    @if($role->name == 'Admin') bg-purple-100 text-purple-800
                                                                    @elseif($role->name == 'Vendor') bg-blue-100 text-blue-800
                                                                    @else bg-green-100 text-green-800
                                                                    @endif
                                                                ">
                                                                    {{ $role->name }}
                                                                </span>
                                                            @endforeach
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ ucfirst($user->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $user->created_at->format('M d, Y') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <div class="flex justify-end space-x-2">
                                                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                @if($user->id != auth()->id())
                                                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="{{ $user->status == 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                                                            <i class="fas {{ $user->status == 'active' ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->appends(request()->query())->links() }}
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
