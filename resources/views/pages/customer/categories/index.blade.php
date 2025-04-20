@extends('layouts.app')

@section('content')
<div class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Print Categories</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <a href="{{ route('categories.show', $category->slug ?? $category->id) }}">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-print text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-2">{{ $category->name }}</h2>
                            <p class="text-gray-600 line-clamp-2">{{ $category->description ?? 'Explore our ' . $category->name . ' printing services' }}</p>
                            
                            @if($category->services_count ?? ($category->services && $category->services->count()))
                                <div class="mt-3 text-sm text-blue-600">
                                    {{ $category->services_count ?? ($category->services ? $category->services->count() : 0) }} services available
                                </div>
                            @endif
                            
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    @if($category->children_count ?? ($category->children && $category->children->count()))
                                        {{ $category->children_count ?? $category->children->count() }} subcategories
                                    @endif
                                </span>
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium leading-none text-blue-600 bg-blue-100 rounded-full">
                                    View Details
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
                    <i class="fas fa-folder-open text-4xl text-gray-400 mb-3"></i>
                    <h3 class="text-xl font-medium text-gray-700">No categories found</h3>
                    <p class="text-gray-500 mt-2">Check back later for new printing categories.</p>
                </div>
            @endforelse
        </div>
        
        @if(method_exists($categories, 'links') && $categories->hasPages())
            <div class="mt-8">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
