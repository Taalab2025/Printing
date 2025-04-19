<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\ServiceMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Service::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply category filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }
        
        // Apply vendor filter
        if ($request->has('vendor_id') && !empty($request->vendor_id)) {
            $query->where('vendor_id', $request->vendor_id);
        }
        
        // Apply price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('base_price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('base_price', '<=', $request->max_price);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $services = $query->with(['category', 'vendor'])->paginate(10);
        $categories = Category::where('status', 'active')->get();
        
        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new service.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'turnaround_time' => ['required', 'string', 'max:100'],
            'featured' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'max:5120'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.name' => ['required', 'string', 'max:100'],
            'specifications.*.value' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $serviceData = $request->except(['thumbnail', 'media', 'specifications']);
        $serviceData['slug'] = Str::slug($request->title);
        $serviceData['specifications'] = $request->specifications ?? [];
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('services/thumbnails', 'public');
            $serviceData['thumbnail'] = $thumbnailPath;
        }
        
        $service = Service::create($serviceData);
        
        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $mediaFile) {
                $mediaPath = $mediaFile->store('services/media', 'public');
                
                ServiceMedia::create([
                    'service_id' => $service->id,
                    'file_path' => $mediaPath,
                    'file_type' => $mediaFile->getClientMimeType(),
                    'file_size' => $mediaFile->getSize(),
                    'file_name' => $mediaFile->getClientOriginalName(),
                ]);
            }
        }
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $service->load(['category', 'vendor', 'media']);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $categories = Category::where('status', 'active')->get();
        $service->load('media');
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'turnaround_time' => ['required', 'string', 'max:100'],
            'featured' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'max:5120'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.name' => ['required', 'string', 'max:100'],
            'specifications.*.value' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $serviceData = $request->except(['thumbnail', 'media', 'specifications']);
        $serviceData['slug'] = Str::slug($request->title);
        $serviceData['specifications'] = $request->specifications ?? [];
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($service->thumbnail) {
                Storage::disk('public')->delete($service->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('services/thumbnails', 'public');
            $serviceData['thumbnail'] = $thumbnailPath;
        }
        
        $service->update($serviceData);
        
        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $mediaFile) {
                $mediaPath = $mediaFile->store('services/media', 'public');
                
                ServiceMedia::create([
                    'service_id' => $service->id,
                    'file_path' => $mediaPath,
                    'file_type' => $mediaFile->getClientMimeType(),
                    'file_size' => $mediaFile->getSize(),
                    'file_name' => $mediaFile->getClientOriginalName(),
                ]);
            }
        }
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        // Check if service has orders
        if ($service->orders()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete service with associated orders.');
        }
        
        // Delete service thumbnail
        if ($service->thumbnail) {
            Storage::disk('public')->delete($service->thumbnail);
        }
        
        // Delete service media
        foreach ($service->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Remove the specified media from storage.
     *
     * @param  \App\Models\ServiceMedia  $media
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia(ServiceMedia $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
        
        return redirect()->back()
            ->with('success', 'Media deleted successfully.');
    }

    /**
     * Display services for the frontend.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function frontendIndex(Request $request)
    {
        $query = Service::where('status', 'active');
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        
        // Apply price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('base_price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('base_price', '<=', $request->max_price);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'featured';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'featured':
            default:
                $query->where('featured', true)->latest();
                break;
        }
        
        $services = $query->with(['category', 'vendor'])->paginate(12);
        $categories = Category::where('status', 'active')->get();
        
        return view('customer.services.index', compact('services', 'categories'));
    }

    /**
     * Display a specific service for the frontend.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function frontendShow($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'vendor', 'media'])
            ->firstOrFail();
        
        $relatedServices = Service::where('status', 'active')
            ->where('id', '!=', $service->id)
            ->where(function($query) use ($service) {
                $query->where('category_id', $service->category_id)
                    ->orWhere('vendor_id', $service->vendor_id);
            })
            ->limit(4)
            ->get();
        
        return view('customer.services.show', compact('service', 'relatedServices'));
    }

    /**
     * Display vendor services for the vendor dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorServices(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $query = Service::where('vendor_id', $vendor->id);
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply category filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $services = $query->with('category')->paginate(10);
        $categories = Category::where('status', 'active')->get();
        
        return view('vendor.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new service for the vendor.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorCreate()
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $categories = Category::where('status', 'active')->get();
        return view('vendor.services.create', compact('categories'));
    }

    /**
     * Store a newly created service for the vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorStore(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'turnaround_time' => ['required', 'string', 'max:100'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'max:5120'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.name' => ['required', 'string', 'max:100'],
            'specifications.*.value' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $serviceData = $request->except(['thumbnail', 'media', 'specifications']);
        $serviceData['vendor_id'] = $vendor->id;
        $serviceData['slug'] = Str::slug($request->title);
        $serviceData['specifications'] = $request->specifications ?? [];
        $serviceData['status'] = 'draft'; // New services start as drafts
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('services/thumbnails', 'public');
            $serviceData['thumbnail'] = $thumbnailPath;
        }
        
        $service = Service::create($serviceData);
        
        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $mediaFile) {
                $mediaPath = $mediaFile->store('services/media', 'public');
                
                ServiceMedia::create([
                    'service_id' => $service->id,
                    'file_path' => $mediaPath,
                    'file_type' => $mediaFile->getClientMimeType(),
                    'file_size' => $mediaFile->getSize(),
                    'file_name' => $mediaFile->getClientOriginalName(),
                ]);
            }
        }
        
        return redirect()->route('vendor.services.index')
            ->with('success', 'Service created successfully and saved as draft.');
    }

    /**
     * Show the form for editing a vendor's service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function vendorEdit(Service $service)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if service belongs to vendor
        if ($service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.services.index')
                ->with('error', 'You do not have permission to edit this service.');
        }
        
        $categories = Category::where('status', 'active')->get();
        $service->load('media');
        return view('vendor.services.edit', compact('service', 'categories'));
    }

    /**
     * Update a vendor's service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function vendorUpdate(Request $request, Service $service)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if service belongs to vendor
        if ($service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.services.index')
                ->with('error', 'You do not have permission to edit this service.');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'turnaround_time' => ['required', 'string', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'active'])],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'max:5120'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.name' => ['required', 'string', 'max:100'],
            'specifications.*.value' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $serviceData = $request->except(['thumbnail', 'media', 'specifications']);
        $serviceData['slug'] = Str::slug($request->title);
        $serviceData['specifications'] = $request->specifications ?? [];
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($service->thumbnail) {
                Storage::disk('public')->delete($service->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('services/thumbnails', 'public');
            $serviceData['thumbnail'] = $thumbnailPath;
        }
        
        $service->update($serviceData);
        
        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $mediaFile) {
                $mediaPath = $mediaFile->store('services/media', 'public');
                
                ServiceMedia::create([
                    'service_id' => $service->id,
                    'file_path' => $mediaPath,
                    'file_type' => $mediaFile->getClientMimeType(),
                    'file_size' => $mediaFile->getSize(),
                    'file_name' => $mediaFile->getClientOriginalName(),
                ]);
            }
        }
        
        return redirect()->route('vendor.services.index')
            ->with('success', 'Service updated successfully.');
    }
}
