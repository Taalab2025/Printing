@extends('layouts.app')

@section('title', 'Request a Quote')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="{{ url('/') }}" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Home</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('services.show', $service) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $service->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">Request a Quote</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Request a Quote for {{ $service->name }}</h1>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('quotes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                
                <!-- Service Information -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Service Information</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-medium">Service:</div>
                            <div>{{ $service->name }}</div>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-medium">Vendor:</div>
                            <div>{{ $service->vendor->name }}</div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="font-medium">Base Price:</div>
                            <div>{{ $service->formatted_price }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Quote Details -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Quote Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <select id="quantity" name="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @foreach($service->quantities as $quantity)
                                    <option value="{{ $quantity->value }}">{{ $quantity->label }}</option>
                                @endforeach
                                <option value="custom">Custom quantity</option>
                            </select>
                            
                            <div id="custom-quantity-container" class="hidden mt-3">
                                <label for="custom_quantity" class="block text-sm font-medium text-gray-700 mb-1">Custom Quantity</label>
                                <input type="number" id="custom_quantity" name="custom_quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" min="1">
                            </div>
                        </div>
                        
                        <div>
                            <label for="paper_type" class="block text-sm font-medium text-gray-700 mb-1">Paper Type</label>
                            <select id="paper_type" name="paper_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @foreach($service->paperTypes as $paperType)
                                    <option value="{{ $paperType->id }}">{{ $paperType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="printing" class="block text-sm font-medium text-gray-700 mb-1">Printing</label>
                            <select id="printing" name="printing" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @foreach($service->printingOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="finishing" class="block text-sm font-medium text-gray-700 mb-1">Finishing Options</label>
                            <select id="finishing" name="finishing" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Standard (No Special Finish)</option>
                                @foreach($service->finishingOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-1">Required Delivery Date</label>
                        <input type="date" id="delivery_date" name="delivery_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" min="{{ date('Y-m-d', strtotime('+' . $service->min_production_days . ' days')) }}">
                        <p class="mt-1 text-sm text-gray-500">Standard production time is {{ $service->min_production_days }}-{{ $service->max_production_days }} business days</p>
                    </div>
                    
                    <div>
                        <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                        <textarea id="requirements" name="requirements" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Describe any special requirements or customizations you need..."></textarea>
                    </div>
                </div>
                
                <!-- Upload Design Files -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Upload Design Files</h2>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4">
                        <div class="text-gray-400 mb-2">
                            <i class="fas fa-cloud-upload-alt text-3xl"></i>
                        </div>
                        <p class="mb-2">Drag and drop your design files here or click to browse</p>
                        <p class="text-sm text-gray-500 mb-4">Accepted formats: PDF, AI, PSD, JPG (300 DPI minimum)</p>
                        <input type="file" id="design_files" name="design_files[]" class="hidden" multiple accept=".pdf,.ai,.psd,.jpg,.jpeg,.png">
                        <button type="button" onclick="document.getElementById('design_files').click()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Browse Files
                        </button>
                    </div>
                    
                    <div id="file-list" class="space-y-2 mb-4"></div>
                    
                    <div class="flex items-center">
                        <input id="need_design" name="need_design" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="need_design" class="ml-2 block text-sm text-gray-900">
                            I don't have design files yet, I need design assistance
                        </label>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                        
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company Name (Optional)</label>
                            <input type="text" id="company" name="company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ auth()->user()->company ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>{{ auth()->user()->address ?? '' }}</textarea>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                            <label for="terms" class="ml-2 block text-sm text-gray-900">
                                I agree to the <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-800">terms and conditions</a> and <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-800">privacy policy</a>
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input id="multi_quotes" name="multi_quotes" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="multi_quotes" class="ml-2 block text-sm text-gray-900">
                                Send me quotes from other vendors for this service
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="p-6 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                        Submit Quote Request
                    </button>
                    <a href="{{ route('services.show', $service) }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Show/hide custom quantity field
    const quantitySelect = document.getElementById('quantity');
    const customQuantityContainer = document.getElementById('custom-quantity-container');
    
    quantitySelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customQuantityContainer.classList.remove('hidden');
        } else {
            customQuantityContainer.classList.add('hidden');
        }
    });
    
    // File upload preview
    const fileInput = document.getElementById('design_files');
    const fileList = document.getElementById('file-list');
    
    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        
        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
            
            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex items-center';
            
            const fileIcon = document.createElement('i');
            fileIcon.className = 'fas fa-file mr-2 text-blue-500';
            
            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            
            const fileSize = document.createElement('span');
            fileSize.className = 'text-sm text-gray-500 ml-2';
            fileSize.textContent = formatFileSize(file.size);
            
            fileInfo.appendChild(fileIcon);
            fileInfo.appendChild(fileName);
            fileInfo.appendChild(fileSize);
            
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-red-500 hover:text-red-700';
            removeButton.innerHTML = '<i class="fas fa-times"></i>';
            removeButton.addEventListener('click', function() {
                fileItem.remove();
                // Note: This doesn't actually remove the file from the input
                // In a real implementation, you would need to create a new FileList
            });
            
            fileItem.appendChild(fileInfo);
            fileItem.appendChild(removeButton);
            fileList.appendChild(fileItem);
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>
@endpush
