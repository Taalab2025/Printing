<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply parent category filter
        if ($request->has('parent_id') && !is_null($request->parent_id)) {
            $query->where('parent_id', $request->parent_id);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'position';
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'position':
            default:
                $query->orderBy('position', 'asc');
                break;
        }
        
        $categories = $query->withCount('services')->paginate(15);
        $parentCategories = Category::whereNull('parent_id')->get();
        
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'icon' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $categoryData = $request->except(['image']);
        $categoryData['slug'] = Str::slug($request->name);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $categoryData['image'] = $imagePath;
        }
        
        Category::create($categoryData);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'services']);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'icon' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Prevent category from being its own parent
        if ($request->parent_id == $category->id) {
            return redirect()->back()
                ->withErrors(['parent_id' => 'A category cannot be its own parent.'])
                ->withInput();
        }
        
        $categoryData = $request->except(['image']);
        $categoryData['slug'] = Str::slug($request->name);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store('categories', 'public');
            $categoryData['image'] = $imagePath;
        }
        
        $category->update($categoryData);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Check if category has services
        if ($category->services()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated services.');
        }
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with subcategories.');
        }
        
        // Delete category image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Update the positions of multiple categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePositions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'positions' => ['required', 'array'],
            'positions.*.id' => ['required', 'exists:categories,id'],
            'positions.*.position' => ['required', 'integer', 'min:0'],
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        foreach ($request->positions as $item) {
            Category::where('id', $item['id'])->update(['position' => $item['position']]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Display categories for the frontend.
     *
     * @return \Illuminate\Http\Response
     */
    public function frontendIndex()
    {
        $categories = Category::where('status', 'active')
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('position')
            ->get();
        
        return view('customer.categories.index', compact('categories'));
    }

    /**
     * Display a specific category with its services for the frontend.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function frontendShow($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        
        $services = $category->services()
            ->where('status', 'active')
            ->with('vendor')
            ->paginate(12);
        
        $relatedCategories = Category::where('status', 'active')
            ->where(function($query) use ($category) {
                $query->where('parent_id', $category->parent_id)
                    ->orWhere('parent_id', $category->id);
            })
            ->where('id', '!=', $category->id)
            ->limit(5)
            ->get();
        
        return view('customer.categories.show', compact('category', 'services', 'relatedCategories'));
    }
}
