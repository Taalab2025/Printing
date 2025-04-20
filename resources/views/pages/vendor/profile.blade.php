@extends('layouts.app')

@section('title', 'Vendor Profile')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <a href="{{ route('vendor.dashboard') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Vendor Dashboard</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">Profile</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Vendor Profile</h3>
                <p class="mt-1 text-sm text-gray-600">
                    This information will be displayed publicly so be careful what you share.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            @if(session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Company Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Company Logo
                            </label>
                            <div class="mt-1 flex items-center">
                                <div class="h-24 w-24 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                    @if($vendor->logo)
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="fas fa-store text-gray-300 text-4xl"></i>
                                    @endif
                                </div>
                                <div class="ml-5">
                                    <div class="relative">
                                        <input type="file" name="logo" id="logo" class="sr-only" accept="image/*">
                                        <label for="logo" class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                            Change
                                        </label>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                            @error('logo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Photo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Cover Photo
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    @if($vendor->cover_photo)
                                        <img src="{{ asset('storage/' . $vendor->cover_photo) }}" alt="Cover Photo" class="mx-auto h-32 object-cover">
                                    @else
                                        <i class="fas fa-image mx-auto h-12 w-12 text-gray-400"></i>
                                    @endif
                                    <div class="flex text-sm text-gray-600">
                                        <label for="cover_photo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="cover_photo" name="cover_photo" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 5MB
                                    </p>
                                </div>
                            </div>
                            @error('cover_photo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">Company Name (English)</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="name_ar" class="block text-sm font-medium text-gray-700">Company Name (Arabic)</label>
                                <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $vendor->name_ar) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name_ar')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="tagline" class="block text-sm font-medium text-gray-700">Tagline</label>
                                <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $vendor->tagline) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('tagline')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description (English)</label>
                                <textarea id="description" name="description" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $vendor->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="description_ar" class="block text-sm font-medium text-gray-700">Description (Arabic)</label>
                                <textarea id="description_ar" name="description_ar" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description_ar', $vendor->description_ar) }}</textarea>
                                @error('description_ar')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $vendor->website) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('website')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="founded_year" class="block text-sm font-medium text-gray-700">Year Founded</label>
                                <input type="number" name="founded_year" id="founded_year" value="{{ old('founded_year', $vendor->founded_year) }}" min="1900" max="{{ date('Y') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('founded_year')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Location Information</h3>
                            <div class="mt-4 grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
                                    <input type="text" name="address" id="address" value="{{ old('address', $vendor->address) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $vendor->city) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('city')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                                    <input type="text" name="state" id="state" value="{{ old('state', $vendor->state) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('state')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $vendor->postal_code) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('postal_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <select id="country" name="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Select a country</option>
                                        @foreach($countries as $countryCode => $countryName)
                                            <option value="{{ $countryCode }}" {{ old('country', $vendor->country) == $countryCode ? 'selected' : '' }}>{{ $countryName }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Social Media</h3>
                            <div class="mt-4 grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fab fa-facebook"></i>
                                        </span>
                                        <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $vendor->facebook) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300">
                                    </div>
                                    @error('facebook')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fab fa-instagram"></i>
                                        </span>
                                        <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $vendor->instagram) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300">
                                    </div>
                                    @error('instagram')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="twitter" class="block text-sm font-medium text-gray-700">Twitter</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fab fa-twitter"></i>
                                        </span>
                                        <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $vendor->twitter) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300">
                                    </div>
                                    @error('twitter')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <i class="fab fa-linkedin"></i>
                                        </span>
                                        <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $vendor->linkedin) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300">
                                    </div>
                                    @error('linkedin')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Business Information</h3>
                            <div class="mt-4 grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type</label>
                                    <select id="business_type" name="business_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Select a business type</option>
                                        <option value="sole_proprietorship" {{ old('business_type', $vendor->business_type) == 'sole_proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                                        <option value="partnership" {{ old('business_type', $vendor->business_type) == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                        <option value="llc" {{ old('business_type', $vendor->business_type) == 'llc' ? 'selected' : '' }}>Limited Liability Company (LLC)</option>
                                        <option value="corporation" {{ old('business_type', $vendor->business_type) == 'corporation' ? 'selected' : '' }}>Corporation</option>
                                        <option value="other" {{ old('business_type', $vendor->business_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('business_type')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="tax_id" class="block text-sm font-medium text-gray-700">Tax ID / VAT Number</label>
                                    <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $vendor->tax_id) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('tax_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="license_number" class="block text-sm font-medium text-gray-700">Business License Number</label>
                                    <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $vendor->license_number) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('license_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="employees_count" class="block text-sm font-medium text-gray-700">Number of Employees</label>
                                    <select id="employees_count" name="employees_count" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Select range</option>
                                        <option value="1-5" {{ old('employees_count', $vendor->employees_count) == '1-5' ? 'selected' : '' }}>1-5</option>
                                        <option value="6-10" {{ old('employees_count', $vendor->employees_count) == '6-10' ? 'selected' : '' }}>6-10</option>
                                        <option value="11-50" {{ old('employees_count', $vendor->employees_count) == '11-50' ? 'selected' : '' }}>11-50</option>
                                        <option value="51-100" {{ old('employees_count', $vendor->employees_count) == '51-100' ? 'selected' : '' }}>51-100</option>
                                        <option value="101-500" {{ old('employees_count', $vendor->employees_count) == '101-500' ? 'selected' : '' }}>101-500</option>
                                        <option value="500+" {{ old('employees_count', $vendor->employees_count) == '500+' ? 'selected' : '' }}>500+</option>
                                    </select>
                                    @error('employees_count')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="hidden sm:block" aria-hidden="true">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="mt-10 sm:mt-0 mb-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Payment Information</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Set up your payment methods and payout details.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('vendor.payment.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $vendor->bank_name) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('bank_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="bank_account_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                    <input type="text" name="bank_account_name" id="bank_account_name" value="{{ old('bank_account_name', $vendor->bank_account_name) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('bank_account_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="bank_account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                    <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $vendor->bank_account_number) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('bank_account_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="bank_routing_number" class="block text-sm font-medium text-gray-700">Routing Number / SWIFT / IBAN</label>
                                    <input type="text" name="bank_routing_number" id="bank_routing_number" value="{{ old('bank_routing_number', $vendor->bank_routing_number) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('bank_routing_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6">
                                    <label for="payment_methods" class="block text-sm font-medium text-gray-700">Accepted Payment Methods</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center">
                                            <input id="payment_credit_card" name="payment_methods[]" type="checkbox" value="credit_card" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('credit_card', old('payment_methods', $vendor->payment_methods ?? [])) ? 'checked' : '' }}>
                                            <label for="payment_credit_card" class="ml-2 text-sm text-gray-700">Credit Card</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="payment_paypal" name="payment_methods[]" type="checkbox" value="paypal" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('paypal', old('payment_methods', $vendor->payment_methods ?? [])) ? 'checked' : '' }}>
                                            <label for="payment_paypal" class="ml-2 text-sm text-gray-700">PayPal</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="payment_bank_transfer" name="payment_methods[]" type="checkbox" value="bank_transfer" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('bank_transfer', old('payment_methods', $vendor->payment_methods ?? [])) ? 'checked' : '' }}>
                                            <label for="payment_bank_transfer" class="ml-2 text-sm text-gray-700">Bank Transfer</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="payment_cash" name="payment_methods[]" type="checkbox" value="cash" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ in_array('cash', old('payment_methods', $vendor->payment_methods ?? [])) ? 'checked' : '' }}>
                                            <label for="payment_cash" class="ml-2 text-sm text-gray-700">Cash on Delivery</label>
                                        </div>
                                    </div>
                                    @error('payment_methods')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Payment Information
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
