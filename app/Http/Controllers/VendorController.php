<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Vendor::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply location filter
        if ($request->has('location') && !empty($request->location)) {
            $location = $request->location;
            $query->where(function($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('state', 'like', "%{$location}%")
                  ->orWhere('country', 'like', "%{$location}%");
            });
        }
        
        // Apply subscription filter
        if ($request->has('subscription') && !empty($request->subscription)) {
            $query->where('subscription_plan_id', $request->subscription);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $vendors = $query->with('subscriptionPlan')->withCount('services')->paginate(10);
        $subscriptionPlans = SubscriptionPlan::all();
        
        return view('admin.vendors.index', compact('vendors', 'subscriptionPlans'));
    }

    /**
     * Show the form for creating a new vendor.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriptionPlans = SubscriptionPlan::all();
        $users = User::whereDoesntHave('vendor')->get();
        return view('admin.vendors.create', compact('subscriptionPlans', 'users'));
    }

    /**
     * Store a newly created vendor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
            'phone' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:5120'],
            'website' => ['nullable', 'url', 'max:255'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'user_id' => ['required', 'exists:users,id', 'unique:vendors,user_id'],
            'status' => ['required', Rule::in(['pending', 'active', 'suspended'])],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $vendorData = $request->except(['logo', 'cover_photo']);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            $vendorData['logo'] = $logoPath;
        }
        
        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            $coverPath = $request->file('cover_photo')->store('vendors/covers', 'public');
            $vendorData['cover_photo'] = $coverPath;
        }
        
        $vendor = Vendor::create($vendorData);
        
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified vendor.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['user', 'subscriptionPlan', 'services']);
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        $subscriptionPlans = SubscriptionPlan::all();
        $users = User::whereDoesntHave('vendor')
            ->orWhere('id', $vendor->user_id)
            ->get();
        return view('admin.vendors.edit', compact('vendor', 'subscriptionPlans', 'users'));
    }

    /**
     * Update the specified vendor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('vendors')->ignore($vendor->id)],
            'phone' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:5120'],
            'website' => ['nullable', 'url', 'max:255'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'user_id' => ['required', 'exists:users,id', Rule::unique('vendors')->ignore($vendor->id)],
            'status' => ['required', Rule::in(['pending', 'active', 'suspended'])],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $vendorData = $request->except(['logo', 'cover_photo']);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            
            $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            $vendorData['logo'] = $logoPath;
        }
        
        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($vendor->cover_photo) {
                Storage::disk('public')->delete($vendor->cover_photo);
            }
            
            $coverPath = $request->file('cover_photo')->store('vendors/covers', 'public');
            $vendorData['cover_photo'] = $coverPath;
        }
        
        $vendor->update($vendorData);
        
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Update the vendor's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(['pending', 'active', 'suspended'])],
            'status_notes' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $vendor->status = $request->status;
        $vendor->status_notes = $request->status_notes;
        $vendor->save();
        
        // Send notification to vendor about status change
        // TODO: Implement notification system
        
        return redirect()->back()
            ->with('success', 'Vendor status updated successfully.');
    }

    /**
     * Remove the specified vendor from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        // Delete vendor logo and cover photo
        if ($vendor->logo) {
            Storage::disk('public')->delete($vendor->logo);
        }
        
        if ($vendor->cover_photo) {
            Storage::disk('public')->delete($vendor->cover_photo);
        }
        
        // Delete vendor
        $vendor->delete();
        
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    /**
     * Display the vendor profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $countries = $this->getCountriesList();
        
        return view('vendor.profile', compact('vendor', 'countries'));
    }

    /**
     * Update the vendor's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_ar' => ['nullable', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('vendors')->ignore($vendor->id)],
            'phone' => ['required', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'business_type' => ['nullable', 'string', Rule::in(['sole_proprietorship', 'partnership', 'llc', 'corporation', 'other'])],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'license_number' => ['nullable', 'string', 'max:50'],
            'employees_count' => ['nullable', 'string', Rule::in(['1-5', '6-10', '11-50', '51-100', '101-500', '500+'])],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:5120'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $vendorData = $request->except(['logo', 'cover_photo']);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            
            $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            $vendorData['logo'] = $logoPath;
        }
        
        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($vendor->cover_photo) {
                Storage::disk('public')->delete($vendor->cover_photo);
            }
            
            $coverPath = $request->file('cover_photo')->store('vendors/covers', 'public');
            $vendorData['cover_photo'] = $coverPath;
        }
        
        $vendor->update($vendorData);
        
        return redirect()->route('vendor.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the vendor's payment information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePayment(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $validator = Validator::make($request->all(), [
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_routing_number' => ['nullable', 'string', 'max:50'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string', Rule::in(['credit_card', 'paypal', 'bank_transfer', 'cash'])],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $vendor->update([
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_routing_number' => $request->bank_routing_number,
            'payment_methods' => $request->payment_methods ?? [],
        ]);
        
        return redirect()->route('vendor.profile')
            ->with('success', 'Payment information updated successfully.');
    }

    /**
     * Get a list of countries for dropdown.
     *
     * @return array
     */
    private function getCountriesList()
    {
        return [
            'AE' => 'United Arab Emirates',
            'BH' => 'Bahrain',
            'EG' => 'Egypt',
            'JO' => 'Jordan',
            'KW' => 'Kuwait',
            'LB' => 'Lebanon',
            'OM' => 'Oman',
            'QA' => 'Qatar',
            'SA' => 'Saudi Arabia',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            // Add more countries as needed
        ];
    }
}
