<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'Business Cards',
                'name_ar' => 'بطاقات العمل',
                'description_en' => 'Professional business cards in various sizes and materials',
                'description_ar' => 'بطاقات عمل احترافية بأحجام ومواد مختلفة',
                'icon' => 'business_card.svg',
                'parent_id' => null
            ],
            [
                'name_en' => 'Banners & Signs',
                'name_ar' => 'لافتات وإشارات',
                'description_en' => 'Indoor and outdoor banners, signs, and posters',
                'description_ar' => 'لافتات داخلية وخارجية وإشارات وملصقات',
                'icon' => 'banner.svg',
                'parent_id' => null
            ],
            [
                'name_en' => 'Brochures & Flyers',
                'name_ar' => 'كتيبات ونشرات',
                'description_en' => 'Marketing materials including brochures, flyers, and leaflets',
                'description_ar' => 'مواد تسويقية تشمل الكتيبات والنشرات والمنشورات',
                'icon' => 'brochure.svg',
                'parent_id' => null
            ],
            [
                'name_en' => 'Stationery',
                'name_ar' => 'قرطاسية',
                'description_en' => 'Letterheads, envelopes, and other office stationery',
                'description_ar' => 'أوراق رسمية وظروف وقرطاسية مكتبية أخرى',
                'icon' => 'stationery.svg',
                'parent_id' => null
            ],
            [
                'name_en' => 'Promotional Items',
                'name_ar' => 'عناصر ترويجية',
                'description_en' => 'Branded merchandise and promotional products',
                'description_ar' => 'بضائع تحمل العلامة التجارية ومنتجات ترويجية',
                'icon' => 'promotional.svg',
                'parent_id' => null
            ],
            [
                'name_en' => 'Packaging',
                'name_ar' => 'تغليف',
                'description_en' => 'Custom packaging solutions for products',
                'description_ar' => 'حلول تغليف مخصصة للمنتجات',
                'icon' => 'packaging.svg',
                'parent_id' => null
            ],
            [
                'name_en' => 'Large Format Printing',
                'name_ar' => 'طباعة بتنسيق كبير',
                'description_en' => 'Large format prints for exhibitions and events',
                'description_ar' => 'مطبوعات كبيرة الحجم للمعارض والفعاليات',
                'icon' => 'large_format.svg',
                'parent_id' => null
            ],
        ];

        // Create subcategories for some main categories
        $subcategories = [
            // Business Cards subcategories
            [
                'name_en' => 'Standard Business Cards',
                'name_ar' => 'بطاقات عمل قياسية',
                'description_en' => 'Standard size business cards with various paper options',
                'description_ar' => 'بطاقات عمل بحجم قياسي مع خيارات ورق متنوعة',
                'icon' => 'standard_card.svg',
                'parent_id' => 1 // Business Cards
            ],
            [
                'name_en' => 'Premium Business Cards',
                'name_ar' => 'بطاقات عمل فاخرة',
                'description_en' => 'Premium business cards with special finishes and materials',
                'description_ar' => 'بطاقات عمل فاخرة بتشطيبات ومواد خاصة',
                'icon' => 'premium_card.svg',
                'parent_id' => 1 // Business Cards
            ],
            
            // Banners & Signs subcategories
            [
                'name_en' => 'Indoor Banners',
                'name_ar' => 'لافتات داخلية',
                'description_en' => 'Banners for indoor use at events and exhibitions',
                'description_ar' => 'لافتات للاستخدام الداخلي في الفعاليات والمعارض',
                'icon' => 'indoor_banner.svg',
                'parent_id' => 2 // Banners & Signs
            ],
            [
                'name_en' => 'Outdoor Banners',
                'name_ar' => 'لافتات خارجية',
                'description_en' => 'Weather-resistant banners for outdoor advertising',
                'description_ar' => 'لافتات مقاومة للعوامل الجوية للإعلانات الخارجية',
                'icon' => 'outdoor_banner.svg',
                'parent_id' => 2 // Banners & Signs
            ],
            
            // Brochures & Flyers subcategories
            [
                'name_en' => 'Tri-fold Brochures',
                'name_ar' => 'كتيبات ثلاثية الطي',
                'description_en' => 'Standard tri-fold brochures in various sizes',
                'description_ar' => 'كتيبات قياسية ثلاثية الطي بأحجام مختلفة',
                'icon' => 'trifold.svg',
                'parent_id' => 3 // Brochures & Flyers
            ],
            [
                'name_en' => 'Flyers',
                'name_ar' => 'نشرات',
                'description_en' => 'Single-page flyers for promotions and events',
                'description_ar' => 'نشرات من صفحة واحدة للعروض الترويجية والفعاليات',
                'icon' => 'flyer.svg',
                'parent_id' => 3 // Brochures & Flyers
            ],
        ];

        // Create main categories
        foreach ($categories as $category) {
            Category::create([
                'name_en' => $category['name_en'],
                'name_ar' => $category['name_ar'],
                'description_en' => $category['description_en'],
                'description_ar' => $category['description_ar'],
                'icon' => $category['icon'],
                'slug' => Str::slug($category['name_en']),
                'is_active' => true,
                'parent_id' => null
            ]);
        }

        // Create subcategories
        foreach ($subcategories as $subcategory) {
            Category::create([
                'name_en' => $subcategory['name_en'],
                'name_ar' => $subcategory['name_ar'],
                'description_en' => $subcategory['description_en'],
                'description_ar' => $subcategory['description_ar'],
                'icon' => $subcategory['icon'],
                'slug' => Str::slug($subcategory['name_en']),
                'is_active' => true,
                'parent_id' => $subcategory['parent_id']
            ]);
        }
    }
}
