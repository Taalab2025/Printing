<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Categories data with ID property for proper routing
        $categories = [
            ['id' => 1, 'name' => 'Business Cards', 'description' => 'Professional business cards printing', 'icon' => 'fa-id-card'],
            ['id' => 2, 'name' => 'Brochures', 'description' => 'High-quality brochure printing', 'icon' => 'fa-book-open'],
            ['id' => 3, 'name' => 'Banners', 'description' => 'Large format banner printing', 'icon' => 'fa-flag'],
            ['id' => 4, 'name' => 'Flyers', 'description' => 'Promotional flyer printing', 'icon' => 'fa-paper-plane'],
            ['id' => 5, 'name' => 'Posters', 'description' => 'Custom poster printing', 'icon' => 'fa-image']
        ];
        
        $featuredVendors = [
            ['id' => 1, 'name' => 'PrintMaster', 'description' => 'Quality printing services for all your needs', 'logo' => null, 'rating' => 4.8, 'reviews_count' => 125, 'city' => 'Cairo', 'country' => 'Egypt'],
            ['id' => 2, 'name' => 'ColorPrint', 'description' => 'Specialized in high-quality color printing', 'logo' => null, 'rating' => 4.5, 'reviews_count' => 98, 'city' => 'Alexandria', 'country' => 'Egypt'],
            ['id' => 3, 'name' => 'FastPrint', 'description' => 'Quick turnaround for all printing jobs', 'logo' => null, 'rating' => 4.2, 'reviews_count' => 76, 'city' => 'Giza', 'country' => 'Egypt']
        ];
        
        $testimonials = [
            ['rating' => 5, 'content' => 'The quality of printing was excellent and the delivery was on time. Highly recommended!', 'user' => ['name' => 'Ahmed Mohamed', 'role' => 'Business Owner', 'profile_photo_path' => null]],
            ['rating' => 4.5, 'content' => 'Great service and competitive prices. Will definitely use again for my business cards.', 'user' => ['name' => 'Sara Ahmed', 'role' => 'Marketing Manager', 'profile_photo_path' => null]],
            ['rating' => 5, 'content' => 'The platform made it easy to compare different printing services and find the best deal.', 'user' => ['name' => 'Mohamed Ali', 'role' => 'Event Organizer', 'profile_photo_path' => null]]
        ];
        
        // Convert arrays to objects for blade template compatibility
        $categories = json_decode(json_encode($categories));
        $featuredVendors = json_decode(json_encode($featuredVendors));
        $testimonials = json_decode(json_encode($testimonials));
        
        return view('pages.customer.home', compact('categories', 'featuredVendors', 'testimonials'));
    }
}
