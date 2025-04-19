<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProof;
use App\Models\OrderStatusHistory;
use App\Models\Quote;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders for the customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerIndex(Request $request)
    {
        $query = Order::where('user_id', auth()->id());
        
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
        
        $orders = $query->with(['service', 'service.vendor'])->paginate(10);
        
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerCreate(Request $request)
    {
        $quote = null;
        $service = null;
        
        if ($request->has('quote') && !empty($request->quote)) {
            $quote = Quote::findOrFail($request->quote);
            
            // Check if quote belongs to user
            if ($quote->quoteRequest->user_id !== auth()->id()) {
                return redirect()->route('customer.quotes.index')
                    ->with('error', 'You do not have permission to create an order for this quote.');
            }
            
            // Check if quote is accepted
            if ($quote->status !== 'accepted') {
                return redirect()->route('customer.quotes.show', $quote->quoteRequest)
                    ->with('error', 'You need to accept the quote before creating an order.');
            }
            
            $service = $quote->quoteRequest->service;
        } elseif ($request->has('service') && !empty($request->service)) {
            $service = Service::findOrFail($request->service);
            
            // Check if service is active
            if ($service->status !== 'active') {
                return redirect()->route('customer.services.index')
                    ->with('error', 'This service is not available for ordering.');
            }
        } else {
            return redirect()->route('customer.services.index')
                ->with('error', 'Please select a service to order.');
        }
        
        return view('customer.orders.create', compact('quote', 'service'));
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => ['required', 'exists:services,id'],
            'quote_id' => ['nullable', 'exists:quotes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'delivery_address' => ['required', 'string'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'special_instructions' => ['nullable', 'string'],
            'payment_method' => ['required', Rule::in(['credit_card', 'paypal', 'bank_transfer', 'cash'])],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB max per file
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if quote belongs to user if provided
        if ($request->has('quote_id') && !empty($request->quote_id)) {
            $quote = Quote::findOrFail($request->quote_id);
            
            if ($quote->quoteRequest->user_id !== auth()->id()) {
                return redirect()->route('customer.quotes.index')
                    ->with('error', 'You do not have permission to create an order for this quote.');
            }
            
            // Check if quote is accepted
            if ($quote->status !== 'accepted') {
                return redirect()->route('customer.quotes.show', $quote->quoteRequest)
                    ->with('error', 'You need to accept the quote before creating an order.');
            }
        }
        
        $orderData = $request->except(['files']);
        $orderData['user_id'] = auth()->id();
        $orderData['status'] = 'pending';
        $orderData['order_number'] = 'ORD-' . Str::random(8);
        
        $order = Order::create($orderData);
        
        // Create initial status history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'notes' => 'Order created',
            'user_id' => auth()->id(),
        ]);
        
        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('orders/' . $order->id, 'public');
                
                OrderProof::create([
                    'order_id' => $order->id,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_name' => $file->getClientOriginalName(),
                    'uploaded_by' => 'customer',
                ]);
            }
        }
        
        // Notify vendor about new order
        // TODO: Implement notification system
        
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    /**
     * Display the specified order for the customer.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function customerShow(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to view this order.');
        }
        
        $order->load(['service', 'service.vendor', 'proofs', 'statusHistory']);
        
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function customerCancel(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to cancel this order.');
        }
        
        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'This order cannot be cancelled.');
        }
        
        $order->status = 'cancelled';
        $order->save();
        
        // Create status history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'notes' => 'Order cancelled by customer',
            'user_id' => auth()->id(),
        ]);
        
        // Notify vendor about cancelled order
        // TODO: Implement notification system
        
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Upload proof files for the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function customerUploadProof(Request $request, Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to upload proofs for this order.');
        }
        
        $validator = Validator::make($request->all(), [
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB max per file
            'notes' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle file uploads
        foreach ($request->file('files') as $file) {
            $filePath = $file->store('orders/' . $order->id, 'public');
            
            OrderProof::create([
                'order_id' => $order->id,
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'file_name' => $file->getClientOriginalName(),
                'notes' => $request->notes,
                'uploaded_by' => 'customer',
            ]);
        }
        
        // Notify vendor about new proofs
        // TODO: Implement notification system
        
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Proofs uploaded successfully.');
    }

    /**
     * Approve the proof for the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderProof  $proof
     * @return \Illuminate\Http\Response
     */
    public function customerApproveProof(Request $request, OrderProof $proof)
    {
        $order = $proof->order;
        
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to approve proofs for this order.');
        }
        
        // Check if proof was uploaded by vendor
        if ($proof->uploaded_by !== 'vendor') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'You can only approve proofs uploaded by the vendor.');
        }
        
        $proof->approved = true;
        $proof->approval_date = now();
        $proof->save();
        
        // Update order status if needed
        if ($order->status === 'proof_review') {
            $order->status = 'production';
            $order->save();
            
            // Create status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'production',
                'notes' => 'Proof approved by customer, order moved to production',
                'user_id' => auth()->id(),
            ]);
        }
        
        // Notify vendor about approved proof
        // TODO: Implement notification system
        
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Proof approved successfully.');
    }

    /**
     * Reject the proof for the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderProof  $proof
     * @return \Illuminate\Http\Response
     */
    public function customerRejectProof(Request $request, OrderProof $proof)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => ['required', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $order = $proof->order;
        
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You do not have permission to reject proofs for this order.');
        }
        
        // Check if proof was uploaded by vendor
        if ($proof->uploaded_by !== 'vendor') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'You can only reject proofs uploaded by the vendor.');
        }
        
        $proof->approved = false;
        $proof->rejection_reason = $request->rejection_reason;
        $proof->approval_date = now();
        $proof->save();
        
        // Notify vendor about rejected proof
        // TODO: Implement notification system
        
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Proof rejected successfully.');
    }

    /**
     * Display a listing of the orders for the vendor.
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
        
        $query = Order::whereHas('service', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        });
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                $query->orderBy('total_price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('total_price', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $orders = $query->with(['user', 'service'])->paginate(10);
        
        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Display the specified order for the vendor.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function vendorShow(Order $order)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if order belongs to vendor's service
        if ($order->service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.orders.index')
                ->with('error', 'You do not have permission to view this order.');
        }
        
        $order->load(['user', 'service', 'proofs', 'statusHistory']);
        
        return view('vendor.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function vendorUpdateStatus(Request $request, Order $order)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if order belongs to vendor's service
        if ($order->service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.orders.index')
                ->with('error', 'You do not have permission to update this order.');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(['pending', 'processing', 'proof_review', 'production', 'shipping', 'delivered', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if status transition is valid
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['proof_review', 'production', 'cancelled'],
            'proof_review' => ['production', 'cancelled'],
            'production' => ['shipping', 'cancelled'],
            'shipping' => ['delivered'],
            'delivered' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];
        
        if (!in_array($request->status, $validTransitions[$order->status])) {
            return redirect()->back()
                ->with('error', 'Invalid status transition.');
        }
        
        $order->status = $request->status;
        $order->save();
        
        // Create status history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
        ]);
        
        // Notify customer about status update
        // TODO: Implement notification system
        
        return redirect()->route('vendor.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Upload proof files for the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function vendorUploadProof(Request $request, Order $order)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'You need to register as a vendor first.');
        }
        
        // Check if order belongs to vendor's service
        if ($order->service->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.orders.index')
                ->with('error', 'You do not have permission to upload proofs for this order.');
        }
        
        $validator = Validator::make($request->all(), [
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB max per file
            'notes' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle file uploads
        foreach ($request->file('files') as $file) {
            $filePath = $file->store('orders/' . $order->id, 'public');
            
            OrderProof::create([
                'order_id' => $order->id,
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'file_name' => $file->getClientOriginalName(),
                'notes' => $request->notes,
                'uploaded_by' => 'vendor',
            ]);
        }
        
        // Update order status if needed
        if ($order->status === 'processing') {
            $order->status = 'proof_review';
            $order->save();
            
            // Create status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'proof_review',
                'notes' => 'Proofs uploaded, waiting for customer review',
                'user_id' => auth()->id(),
            ]);
        }
        
        // Notify customer about new proofs
        // TODO: Implement notification system
        
        return redirect()->route('vendor.orders.show', $order)
            ->with('success', 'Proofs uploaded successfully.');
    }

    /**
     * Display a listing of the orders for the admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $query = Order::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
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
        
        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Apply sorting
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                $query->orderBy('total_price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('total_price', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $orders = $query->with(['user', 'service', 'service.vendor'])->paginate(15);
        $vendors = Vendor::where('status', 'active')->get();
        
        return view('admin.orders.index', compact('orders', 'vendors'));
    }

    /**
     * Display the specified order for the admin.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function adminShow(Order $order)
    {
        $order->load(['user', 'service', 'service.vendor', 'proofs', 'statusHistory']);
        
        return view('admin.orders.show', compact('order'));
    }
}
