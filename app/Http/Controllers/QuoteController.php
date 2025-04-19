<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequest;
use App\Models\QuoteRequestFile;
use App\Models\Quote;
use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class QuoteController extends Controller
{
    /**
     * Display a listing of the quote requests for the customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerIndex(Request $request)
    {
        $query = QuoteRequest::where('user_id', auth()->id());
        
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
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $quoteRequests = $query->with(['service', 'service.vendor', 'quotes'])->paginate(10);
        
        return view('customer.quotes.index', compact('quoteRequests'));
    }

    /**
     * Show the form for creating a new quote request.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function customerCreate(Service $service = null)
    {
        $services = null;
        
        if (!$service) {
            $services = Service::where('status', 'active')
                ->with('vendor')
                ->get();
        }
        
        return view('customer.quotes.create', compact('service', 'services'));
    }

    /**
     * Store a newly created quote request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => ['required', 'exists:services,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'delivery_address' => ['required', 'string'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB max per file
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $quoteRequestData = $request->except('files');
        $quoteRequestData['user_id'] = auth()->id();
        $quoteRequestData['status'] = 'pending';
        $quoteRequestData['reference_number'] = 'QR-' . Str::random(8);
        
        $quoteRequest = QuoteRequest::create($quoteRequestData);
        
        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('quote_requests/' . $quoteRequest->id, 'public');
                
                QuoteRequestFile::create([
                    'quote_request_id' => $quoteRequest->id,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }
        
        // Notify vendor about new quote request
        // TODO: Implement notification system
        
        return redirect()->route('customer.quotes.show', $quoteRequest)
            ->with('success', 'Quote request submitted successfully.');
    }

    /**
     * Display the specified quote request for the customer.
     *
     * @param  \App\Models\QuoteRequest  $quoteRequest
     * @return \Illuminate\Http\Response
     */
    public function customerShow(QuoteRequest $quoteRequest)
    {
        // Check if quote request belongs to user
        if ($quoteRequest->user_id !== auth()->id()) {
            return redirect()->route('customer.quotes.index')
                ->with('error', 'You do not have permission to view this quote request.');
        }
        
        $quoteRequest->load(['service', 'service.vendor', 'files', 'quotes']);
        
        return view('customer.quotes.show', compact('quoteRequest'));
    }

    /**
     * Cancel the specified quote request.
     *
     * @param  \App\Models\QuoteRequest  $quoteRequest
     * @return \Illuminate\Http\Response
     */
    public function customerCancel(QuoteRequest $quoteRequest)
    {
        // Check if quote request belongs to user
        if ($quoteRequest->user_id !== auth()->id()) {
            return redirect()->route('customer.quotes.index')
                ->with('error', 'You do not have permission to cancel this quote request.');
        }
        
        // Check if quote request can be cancelled
        if (!in_array($quoteRequest->status, ['pending', 'in_progress'])) {
            return redirect()->route('customer.quotes.show', $quoteRequest)
                ->with('error', 'This quote request cannot be cancelled.');
        }
        
        $quoteRequest->status = 'cancelled';
        $quoteRequest->save();
        
        // Notify vendor about cancelled quote request
        // TODO: Implement notification system
        
        return redirect()->route('customer.quotes.show', $quoteRequest)
            ->with('success', 'Quote request cancelled successfully.');
    }

    /**
     * Display a listing of the quote requests for the vendor.
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
        
        $query = QuoteRequest::whereHas('service', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        });
        
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
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $quoteRequests = $query->with(['user', 'service'])->paginate(10);
        
        return view('vendor.quotes.index', compact('quoteRequests'));
    }

    /**
     * Display the specified quote request for the vendor.
     *
     * @param  \App\Models\QuoteRequest  $quoteRequest
     * @return \Illuminate\Http\Response
     */
    public function vendorShow(QuoteRequest $quoteRequest)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if quote request belongs to vendor's service
        if ($quoteRequest->service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.quotes.index')
                ->with('error', 'You do not have permission to view this quote request.');
        }
        
        $quoteRequest->load(['user', 'service', 'files', 'quotes']);
        
        return view('vendor.quotes.show', compact('quoteRequest'));
    }

    /**
     * Show the form for creating a new quote for a quote request.
     *
     * @param  \App\Models\QuoteRequest  $quoteRequest
     * @return \Illuminate\Http\Response
     */
    public function vendorCreateQuote(QuoteRequest $quoteRequest)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if quote request belongs to vendor's service
        if ($quoteRequest->service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.quotes.index')
                ->with('error', 'You do not have permission to create a quote for this request.');
        }
        
        // Check if quote request is in a valid state for quoting
        if (!in_array($quoteRequest->status, ['pending', 'in_progress'])) {
            return redirect()->route('vendor.quotes.show', $quoteRequest)
                ->with('error', 'Cannot create a quote for this request.');
        }
        
        $quoteRequest->load(['user', 'service', 'files']);
        
        return view('vendor.quotes.create', compact('quoteRequest'));
    }

    /**
     * Store a newly created quote in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuoteRequest  $quoteRequest
     * @return \Illuminate\Http\Response
     */
    public function vendorStoreQuote(Request $request, QuoteRequest $quoteRequest)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if quote request belongs to vendor's service
        if ($quoteRequest->service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.quotes.index')
                ->with('error', 'You do not have permission to create a quote for this request.');
        }
        
        // Check if quote request is in a valid state for quoting
        if (!in_array($quoteRequest->status, ['pending', 'in_progress'])) {
            return redirect()->route('vendor.quotes.show', $quoteRequest)
                ->with('error', 'Cannot create a quote for this request.');
        }
        
        $validator = Validator::make($request->all(), [
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'turnaround_time' => ['required', 'string', 'max:100'],
            'valid_until' => ['required', 'date', 'after:today'],
            'terms' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $quoteData = $request->all();
        $quoteData['quote_request_id'] = $quoteRequest->id;
        $quoteData['status'] = 'pending';
        $quoteData['reference_number'] = 'Q-' . Str::random(8);
        
        $quote = Quote::create($quoteData);
        
        // Update quote request status
        if ($quoteRequest->status === 'pending') {
            $quoteRequest->status = 'in_progress';
            $quoteRequest->save();
        }
        
        // Notify customer about new quote
        // TODO: Implement notification system
        
        return redirect()->route('vendor.quotes.show', $quoteRequest)
            ->with('success', 'Quote submitted successfully.');
    }

    /**
     * Accept a quote.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function customerAcceptQuote(Quote $quote)
    {
        $quoteRequest = $quote->quoteRequest;
        
        // Check if quote request belongs to user
        if ($quoteRequest->user_id !== auth()->id()) {
            return redirect()->route('customer.quotes.index')
                ->with('error', 'You do not have permission to accept this quote.');
        }
        
        // Check if quote can be accepted
        if ($quote->status !== 'pending' || !in_array($quoteRequest->status, ['in_progress'])) {
            return redirect()->route('customer.quotes.show', $quoteRequest)
                ->with('error', 'This quote cannot be accepted.');
        }
        
        // Update quote status
        $quote->status = 'accepted';
        $quote->save();
        
        // Update quote request status
        $quoteRequest->status = 'quoted';
        $quoteRequest->save();
        
        // Reject other quotes
        Quote::where('quote_request_id', $quoteRequest->id)
            ->where('id', '!=', $quote->id)
            ->update(['status' => 'rejected']);
        
        // Notify vendor about accepted quote
        // TODO: Implement notification system
        
        // Redirect to order creation
        return redirect()->route('customer.orders.create', ['quote' => $quote->id])
            ->with('success', 'Quote accepted. Please complete your order.');
    }

    /**
     * Reject a quote.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function customerRejectQuote(Quote $quote)
    {
        $quoteRequest = $quote->quoteRequest;
        
        // Check if quote request belongs to user
        if ($quoteRequest->user_id !== auth()->id()) {
            return redirect()->route('customer.quotes.index')
                ->with('error', 'You do not have permission to reject this quote.');
        }
        
        // Check if quote can be rejected
        if ($quote->status !== 'pending' || !in_array($quoteRequest->status, ['in_progress'])) {
            return redirect()->route('customer.quotes.show', $quoteRequest)
                ->with('error', 'This quote cannot be rejected.');
        }
        
        // Update quote status
        $quote->status = 'rejected';
        $quote->save();
        
        // Notify vendor about rejected quote
        // TODO: Implement notification system
        
        return redirect()->route('customer.quotes.show', $quoteRequest)
            ->with('success', 'Quote rejected successfully.');
    }

    /**
     * Display a listing of the quote requests for the admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $query = QuoteRequest::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('reference_number', 'like', "%{$search}%");
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply vendor filter
        if ($request->has('vendor_id') && !empty($request->vendor_id)) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            });
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $quoteRequests = $query->with(['user', 'service', 'service.vendor'])->paginate(15);
        $vendors = Vendor::where('status', 'active')->get();
        
        return view('admin.quotes.index', compact('quoteRequests', 'vendors'));
    }

    /**
     * Display the specified quote request for the admin.
     *
     * @param  \App\Models\QuoteRequest  $quoteRequest
     * @return \Illuminate\Http\Response
     */
    public function adminShow(QuoteRequest $quoteRequest)
    {
        $quoteRequest->load(['user', 'service', 'service.vendor', 'files', 'quotes']);
        
        return view('admin.quotes.show', compact('quoteRequest'));
    }
}
