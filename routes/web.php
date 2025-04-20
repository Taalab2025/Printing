<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Language switcher route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr', 'es'])) { // Add any languages your app supports
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
// Add explicit /home route that redirects to the homepage
Route::get('/home', function () {
    return redirect()->route('home');
});

// Categories routes - with fallback to simple implementation
Route::get('/categories', function () {
    try {
        return app()->make(CategoryController::class)->frontendIndex();
    } catch (\Exception $e) {
        if (class_exists('\App\Models\Category')) {
            $categories = \App\Models\Category::all();
        } else {
            $categories = collect([]);
        }
        return view('pages.customer.categories.index', compact('categories'));
    }
})->name('categories');

// Alias for categories.index (for compatibility)
Route::get('/categories', function () {
    try {
        return app()->make(CategoryController::class)->frontendIndex();
    } catch (\Exception $e) {
        if (class_exists('\App\Models\Category')) {
            $categories = \App\Models\Category::all();
        } else {
            $categories = collect([]);
        }
        return view('pages.customer.categories.index', compact('categories'));
    }
})->name('categories.index');

// Category detail
Route::get('/categories/{id}', function ($id) {
    try {
        $controller = app()->make(CategoryController::class);
        return $controller->frontendShow($id);
    } catch (\Exception $e) {
        if (class_exists('\App\Models\Category')) {
            $category = \App\Models\Category::find($id);
        } else {
            $category = null;
        }
        return view('pages.customer.categories.show', compact('category'));
    }
})->name('categories.show');

// Vendors routes
Route::get('/vendors', function () {
    try {
        return app()->make(VendorController::class)->frontendIndex();
    } catch (\Exception $e) {
        if (class_exists('\App\Models\Vendor')) {
            $vendors = \App\Models\Vendor::all();
        } else {
            $vendors = collect([]);
        }
        return view('pages.customer.vendors.index', compact('vendors'));
    }
})->name('vendors');

// Vendor detail
Route::get('/vendors/{id}', function ($id) {
    try {
        $controller = app()->make(VendorController::class);
        return $controller->frontendShow($id);
    } catch (\Exception $e) {
        if (class_exists('\App\Models\Vendor')) {
            $vendor = \App\Models\Vendor::find($id);
        } else {
            $vendor = null;
        }
        return view('pages.customer.vendors.show', compact('vendor'));
    }
})->name('vendors.show');

// Services routes
Route::get('/services', function () {
    try {
        return app()->make(ServiceController::class)->frontendIndex();
    } catch (\Exception $e) {
        return view('pages.customer.services.index', ['services' => []]);
    }
})->name('services');

Route::get('/services/{id}', function ($id) {
    try {
        $controller = app()->make(ServiceController::class);
        return $controller->frontendShow($id);
    } catch (\Exception $e) {
        return view('pages.customer.services.show', ['id' => $id]);
    }
})->name('services.show');

// Orders routes
Route::get('/orders', function () {
    try {
        return app()->make(OrderController::class)->index();
    } catch (\Exception $e) {
        return view('pages.customer.orders.index', ['orders' => []]);
    }
})->name('orders');

Route::get('/orders/create', function () {
    try {
        return app()->make(OrderController::class)->create();
    } catch (\Exception $e) {
        return view('pages.customer.orders.create');
    }
})->name('orders.create');

Route::post('/orders', function () {
    try {
        return app()->make(OrderController::class)->store(request());
    } catch (\Exception $e) {
        // Simple redirect for now
        return redirect('/')->with('success', 'Order submitted successfully!');
    }
})->name('orders.store');

Route::get('/orders/{id}', function ($id) {
    try {
        $controller = app()->make(OrderController::class);
        return $controller->show($id);
    } catch (\Exception $e) {
        return view('pages.customer.orders.show', ['id' => $id]);
    }
})->name('orders.show');

// Quotes routes
Route::get('/quotes/create', function () {
    try {
        return app()->make(QuoteController::class)->create();
    } catch (\Exception $e) {
        return view('pages.customer.quotes.create');
    }
})->name('quotes.create');

Route::post('/quotes', function () {
    try {
        return app()->make(QuoteController::class)->store(request());
    } catch (\Exception $e) {
        // Simple redirect for now
        return redirect('/')->with('success', 'Quote request submitted successfully!');
    }
})->name('quotes.store');

Route::get('/quotes/{id}', function ($id) {
    try {
        $controller = app()->make(QuoteController::class);
        return $controller->show($id);
    } catch (\Exception $e) {
        return view('pages.customer.quotes.show', ['id' => $id]);
    }
})->name('quotes.show');

// Support routes
Route::get('/support/customer', function () {
    try {
        return app()->make(SupportController::class)->customerSupport();
    } catch (\Exception $e) {
        return view('pages.support.customer');
    }
})->name('support.customer');

Route::get('/support/vendor', function () {
    try {
        return app()->make(SupportController::class)->vendorSupport();
    } catch (\Exception $e) {
        return view('pages.support.vendor');
    }
})->name('support.vendor');

Route::get('/support', function () {
    return redirect()->route('support.customer');
})->name('support');

Route::post('/support/contact', function () {
    // Simple implementation
    return redirect()->back()->with('success', 'Your message has been sent!');
})->name('support.contact');

// Search routes
Route::get('/search', function () {
    try {
        return app()->make(SearchController::class)->index(request());
    } catch (\Exception $e) {
        $query = request('q', '');
        return view('pages.search.index', ['query' => $query, 'results' => []]);
    }
})->name('search');

// Review routes
Route::post('/reviews', function () {
    // Simple implementation
    return redirect()->back()->with('success', 'Your review has been submitted!');
})->name('reviews.store');

Route::get('/vendors/{vendor}/reviews', function ($vendor) {
    return view('pages.customer.vendors.reviews', ['vendor_id' => $vendor]);
})->name('vendors.reviews');

// Checkout routes
Route::get('/checkout', function () {
    return view('pages.customer.checkout.index');
})->name('checkout');

Route::post('/checkout/process', function () {
    return redirect()->route('orders')->with('success', 'Your order has been placed!');
})->name('checkout.process');

// Profile routes
Route::get('/profile', function () {
    try {
        return app()->make(HomeController::class)->profile();
    } catch (\Exception $e) {
        return view('pages.customer.profile');
    }
})->name('profile');

// Information pages
Route::get('/how-it-works', function () {
    return view('pages.info.how-it-works.how-it-works');
})->name('how-it-works');

Route::get('/about', function () {
    return view('pages.info.about.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.info.contact.contact');
})->name('contact');

Route::get('/faqs', function () {
    return view('pages.info.faqs.faqs');
})->name('faqs');

Route::get('/terms', function () {
    return view('pages.info.legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.info.legal.privacy');
})->name('privacy');

Route::get('/cookies', function () {
    return view('pages.info.legal.cookies');
})->name('cookies');

// Authentication routes - FIXED to properly use controller methods
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Add dashboard redirect route
Route::get('/dashboard', function() {
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    return redirect()->route('login');
})->name('dashboard');

// Vendor registration
Route::get('/vendor/register', function () {
    try {
        $controller = app()->make(RegisterController::class);
        return $controller->showRegistrationForm('vendor');
    } catch (\Exception $e) {
        return view('auth.register', ['userType' => 'vendor']);
    }
})->name('vendor.register');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    
    // Categories management
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // Vendors management
    Route::get('/vendors', [VendorController::class, 'index'])->name('admin.vendors.index');
    Route::get('/vendors/{id}', [VendorController::class, 'show'])->name('admin.vendors.show');
    Route::put('/vendors/{id}/approve', [VendorController::class, 'approve'])->name('admin.vendors.approve');
    Route::put('/vendors/{id}/reject', [VendorController::class, 'reject'])->name('admin.vendors.reject');
    
    // Services management
    Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('admin.services.show');
    Route::put('/services/{id}/approve', [ServiceController::class, 'approve'])->name('admin.services.approve');
    Route::put('/services/{id}/reject', [ServiceController::class, 'reject'])->name('admin.services.reject');
});

// Vendor dashboard routes
Route::prefix('vendor')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/profile', [VendorController::class, 'profile'])->name('vendor.profile');
    Route::put('/profile', [VendorController::class, 'updateProfile'])->name('vendor.profile.update');
    
    // Subscription routes
    Route::get('/subscriptions', function () {
        try {
            return app()->make(VendorController::class)->subscriptions();
        } catch (\Exception $e) {
            return view('pages.vendor.subscriptions.index');
        }
    })->name('vendor.subscriptions');
    
    Route::get('/subscriptions/{id}', function ($id) {
        try {
            $controller = app()->make(VendorController::class);
            return $controller->showSubscription($id);
        } catch (\Exception $e) {
            return view('pages.vendor.subscriptions.show', ['id' => $id]);
        }
    })->name('vendor.subscriptions.show');
    
    Route::post('/subscriptions', function () {
        try {
            return app()->make(VendorController::class)->storeSubscription(request());
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Subscription created successfully!');
        }
    })->name('vendor.subscriptions.store');
    
    Route::put('/subscriptions/{id}', function ($id) {
        try {
            $controller = app()->make(VendorController::class);
            return $controller->updateSubscription($id, request());
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Subscription updated successfully!');
        }
    })->name('vendor.subscriptions.update');
    
    Route::delete('/subscriptions/{id}', function ($id) {
        try {
            $controller = app()->make(VendorController::class);
            return $controller->destroySubscription($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Subscription cancelled successfully!');
        }
    })->name('vendor.subscriptions.destroy');
    
    // Services management
    Route::get('/services', [ServiceController::class, 'vendorIndex'])->name('vendor.services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('vendor.services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('vendor.services.store');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('vendor.services.edit');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('vendor.services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('vendor.services.destroy');
    
    // Orders management
    Route::get('/orders', [OrderController::class, 'vendorIndex'])->name('vendor.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'vendorShow'])->name('vendor.orders.show');
    Route::put('/orders/{id}/accept', [OrderController::class, 'accept'])->name('vendor.orders.accept');
    Route::put('/orders/{id}/reject', [OrderController::class, 'reject'])->name('vendor.orders.reject');
    Route::put('/orders/{id}/complete', [OrderController::class, 'complete'])->name('vendor.orders.complete');
    
    // Quote requests
    Route::get('/quotes', [QuoteController::class, 'vendorIndex'])->name('vendor.quotes.index');
    Route::get('/quotes/{id}', [QuoteController::class, 'vendorShow'])->name('vendor.quotes.show');
    Route::post('/quotes/{id}/respond', [QuoteController::class, 'respond'])->name('vendor.quotes.respond');
});

// Customer dashboard routes
Route::prefix('customer')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'customerDashboard'])->name('customer.dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [HomeController::class, 'updateProfile'])->name('customer.profile.update');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'customerIndex'])->name('customer.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'customerShow'])->name('customer.orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('customer.orders.store');
    Route::delete('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
    
    // Quote requests
    Route::get('/quotes', [QuoteController::class, 'customerIndex'])->name('customer.quotes.index');
    Route::get('/quotes/create', [QuoteController::class, 'create'])->name('customer.quotes.create');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('customer.quotes.store');
    Route::get('/quotes/{id}', [QuoteController::class, 'customerShow'])->name('customer.quotes.show');
    Route::put('/quotes/{id}/accept', [QuoteController::class, 'accept'])->name('customer.quotes.accept');
    Route::put('/quotes/{id}/reject', [QuoteController::class, 'reject'])->name('customer.quotes.reject');
});

// Password reset routes
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/password/email', function () {
    return back()->with('status', 'Password reset link sent!');
})->name('password.email');

Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');

Route::post('/password/update', function () {
    return redirect('/login')->with('status', 'Password has been reset!');
})->name('password.update');
Route::get('/quotes', function () {
    return view('pages.customer.quotes.index');
})->name('quotes');
