<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\VendorResponse;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews for the customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerIndex(Request $request)
    {
        $query = Review::where('user_id', auth()->id());
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $reviews = $query->with(['order', 'order.service', 'order.service.vendor', 'vendorResponse'])->paginate(10);
        
        return view('customer.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function customerCreate(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to review this order.');
        }
        
        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'You can only review completed orders.');
        }
        
        // Check if order already has a review
        if ($order->review) {
            return redirect()->route('customer.reviews.edit', $order->review)
                ->with('info', 'You have already reviewed this order. You can edit your review instead.');
        }
        
        return view('customer.reviews.create', compact('order'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function customerStore(Request $request, Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to review this order.');
        }
        
        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'You can only review completed orders.');
        }
        
        // Check if order already has a review
        if ($order->review) {
            return redirect()->route('customer.reviews.edit', $order->review)
                ->with('info', 'You have already reviewed this order. You can edit your review instead.');
        }
        
        $validator = Validator::make($request->all(), [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'quality_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'service_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'value_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'timeliness_rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $reviewData = $request->all();
        $reviewData['user_id'] = auth()->id();
        $reviewData['order_id'] = $order->id;
        $reviewData['vendor_id'] = $order->service->vendor_id;
        $reviewData['service_id'] = $order->service_id;
        $reviewData['status'] = 'published';
        
        $review = Review::create($reviewData);
        
        // Update vendor average rating
        $this->updateVendorRating($order->service->vendor_id);
        
        // Notify vendor about new review
        // TODO: Implement notification system
        
        return redirect()->route('customer.reviews.index')
            ->with('success', 'Review submitted successfully.');
    }

    /**
     * Show the form for editing the specified review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function customerEdit(Review $review)
    {
        // Check if review belongs to user
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('customer.reviews.index')
                ->with('error', 'You do not have permission to edit this review.');
        }
        
        $review->load('order');
        
        return view('customer.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function customerUpdate(Request $request, Review $review)
    {
        // Check if review belongs to user
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('customer.reviews.index')
                ->with('error', 'You do not have permission to edit this review.');
        }
        
        $validator = Validator::make($request->all(), [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'quality_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'service_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'value_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'timeliness_rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $review->update($request->all());
        
        // Update vendor average rating
        $this->updateVendorRating($review->vendor_id);
        
        // Notify vendor about updated review
        // TODO: Implement notification system
        
        return redirect()->route('customer.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Display a listing of the reviews for the vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorIndex(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        $query = Review::where('vendor_id', $vendor->id);
        
        // Apply rating filter
        if ($request->has('rating') && !empty($request->rating)) {
            $query->where('rating', $request->rating);
        }
        
        // Apply response filter
        if ($request->has('has_response') && $request->has_response === '1') {
            $query->has('vendorResponse');
        } elseif ($request->has('has_response') && $request->has_response === '0') {
            $query->doesntHave('vendorResponse');
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $reviews = $query->with(['user', 'order', 'service', 'vendorResponse'])->paginate(10);
        
        return view('vendor.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified review for the vendor.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function vendorShow(Review $review)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if review belongs to vendor
        if ($review->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.reviews.index')
                ->with('error', 'You do not have permission to view this review.');
        }
        
        $review->load(['user', 'order', 'service', 'vendorResponse']);
        
        return view('vendor.reviews.show', compact('review'));
    }

    /**
     * Show the form for creating a response to a review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function vendorCreateResponse(Review $review)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if review belongs to vendor
        if ($review->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.reviews.index')
                ->with('error', 'You do not have permission to respond to this review.');
        }
        
        // Check if review already has a response
        if ($review->vendorResponse) {
            return redirect()->route('vendor.reviews.edit-response', $review)
                ->with('info', 'You have already responded to this review. You can edit your response instead.');
        }
        
        return view('vendor.reviews.create-response', compact('review'));
    }

    /**
     * Store a newly created response in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function vendorStoreResponse(Request $request, Review $review)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if review belongs to vendor
        if ($review->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.reviews.index')
                ->with('error', 'You do not have permission to respond to this review.');
        }
        
        // Check if review already has a response
        if ($review->vendorResponse) {
            return redirect()->route('vendor.reviews.edit-response', $review)
                ->with('info', 'You have already responded to this review. You can edit your response instead.');
        }
        
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        VendorResponse::create([
            'review_id' => $review->id,
            'vendor_id' => $vendor->id,
            'content' => $request->content,
        ]);
        
        // Notify customer about vendor response
        // TODO: Implement notification system
        
        return redirect()->route('vendor.reviews.show', $review)
            ->with('success', 'Response submitted successfully.');
    }

    /**
     * Show the form for editing a response to a review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function vendorEditResponse(Review $review)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if review belongs to vendor
        if ($review->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.reviews.index')
                ->with('error', 'You do not have permission to edit this response.');
        }
        
        // Check if review has a response
        if (!$review->vendorResponse) {
            return redirect()->route('vendor.reviews.create-response', $review)
                ->with('info', 'You have not responded to this review yet. You can create a response instead.');
        }
        
        return view('vendor.reviews.edit-response', compact('review'));
    }

    /**
     * Update the specified response in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function vendorUpdateResponse(Request $request, Review $review)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if review belongs to vendor
        if ($review->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.reviews.index')
                ->with('error', 'You do not have permission to edit this response.');
        }
        
        // Check if review has a response
        if (!$review->vendorResponse) {
            return redirect()->route('vendor.reviews.create-response', $review)
                ->with('info', 'You have not responded to this review yet. You can create a response instead.');
        }
        
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $review->vendorResponse->update([
            'content' => $request->content,
        ]);
        
        return redirect()->route('vendor.reviews.show', $review)
            ->with('success', 'Response updated successfully.');
    }

    /**
     * Display a listing of the reviews for the admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $query = Review::query();
        
        // Apply vendor filter
        if ($request->has('vendor_id') && !empty($request->vendor_id)) {
            $query->where('vendor_id', $request->vendor_id);
        }
        
        // Apply rating filter
        if ($request->has('rating') && !empty($request->rating)) {
            $query->where('rating', $request->rating);
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $reviews = $query->with(['user', 'vendor', 'service', 'vendorResponse'])->paginate(15);
        $vendors = Vendor::where('status', 'active')->get();
        
        return view('admin.reviews.index', compact('reviews', 'vendors'));
    }

    /**
     * Display the specified review for the admin.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function adminShow(Review $review)
    {
        $review->load(['user', 'vendor', 'service', 'order', 'vendorResponse']);
        
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Update the status of the specified review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function adminUpdateStatus(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(['published', 'hidden', 'flagged'])],
            'admin_notes' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $review->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);
        
        // Update vendor average rating if status changed
        if ($review->isDirty('status')) {
            $this->updateVendorRating($review->vendor_id);
        }
        
        return redirect()->route('admin.reviews.show', $review)
            ->with('success', 'Review status updated successfully.');
    }

    /**
     * Update the vendor's average rating.
     *
     * @param  int  $vendorId
     * @return void
     */
    private function updateVendorRating($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        
        $averageRating = Review::where('vendor_id', $vendorId)
            ->where('status', 'published')
            ->avg('rating');
        
        $vendor->rating = $averageRating ?? 0;
        $vendor->save();
    }
}
