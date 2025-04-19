<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\User;
use App\Models\VendorUser;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get vendor users
        $vendor1 = User::where('email', 'vendor1@printingmarketplace.com')->first();
        $vendor2 = User::where('email', 'vendor2@printingmarketplace.com')->first();

        // Create vendor profiles
        $vendorProfile1 = Vendor::create([
            'user_id' => $vendor1->id,
            'company_name' => 'Printing Solutions Ltd',
            'profile_description_en' => 'We are a full-service printing company with over 10 years of experience in the industry. We specialize in high-quality business cards, brochures, and promotional materials.',
            'profile_description_ar' => 'نحن شركة طباعة متكاملة الخدمات مع أكثر من 10 سنوات من الخبرة في الصناعة. نحن متخصصون في بطاقات العمل والكتيبات والمواد الترويجية عالية الجودة.',
            'logo_path' => 'vendors/printing_solutions_logo.png',
            'location' => 'Cairo',
            'city' => 'Cairo',
            'address' => '123 Printing Street, Downtown Cairo',
            'subscription_status' => 'free',
            'free_quotes_used' => 0,
            'free_quotes_limit' => 10,
            'is_featured' => false,
            'rating' => 4.5,
        ]);

        $vendorProfile2 = Vendor::create([
            'user_id' => $vendor2->id,
            'company_name' => 'Cairo Print Masters',
            'profile_description_en' => 'Cairo Print Masters is a leading printing service provider in Egypt. We offer a wide range of printing services including large format printing, banners, and packaging solutions.',
            'profile_description_ar' => 'القاهرة برنت ماسترز هي مزود خدمة طباعة رائد في مصر. نقدم مجموعة واسعة من خدمات الطباعة بما في ذلك الطباعة بتنسيق كبير واللافتات وحلول التغليف.',
            'logo_path' => 'vendors/cairo_print_masters_logo.png',
            'location' => 'Alexandria',
            'city' => 'Alexandria',
            'address' => '456 Print Avenue, Alexandria',
            'subscription_status' => 'premium',
            'subscription_expiry' => now()->addMonths(3),
            'free_quotes_used' => 10,
            'free_quotes_limit' => 10,
            'is_featured' => true,
            'rating' => 4.8,
        ]);

        // Create vendor user relationships
        VendorUser::create([
            'vendor_id' => $vendorProfile1->id,
            'user_id' => $vendor1->id,
            'role' => 'owner'
        ]);

        VendorUser::create([
            'vendor_id' => $vendorProfile2->id,
            'user_id' => $vendor2->id,
            'role' => 'owner'
        ]);
    }
}
