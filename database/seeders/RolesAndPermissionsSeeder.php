<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            [
                'name' => 'admin',
                'display_name_en' => 'Administrator',
                'display_name_ar' => 'مدير النظام',
                'description_en' => 'Super administrator with full access to all features',
                'description_ar' => 'المدير العام مع وصول كامل إلى جميع الميزات'
            ],
            [
                'name' => 'vendor',
                'display_name_en' => 'Vendor',
                'display_name_ar' => 'بائع',
                'description_en' => 'Printing service provider',
                'description_ar' => 'مزود خدمة الطباعة'
            ],
            [
                'name' => 'customer',
                'display_name_en' => 'Customer',
                'display_name_ar' => 'عميل',
                'description_en' => 'Regular user who can request printing services',
                'description_ar' => 'مستخدم عادي يمكنه طلب خدمات الطباعة'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create permissions
        $permissions = [
            // User management permissions
            [
                'name' => 'manage_users',
                'display_name_en' => 'Manage Users',
                'display_name_ar' => 'إدارة المستخدمين',
                'description_en' => 'Create, edit, and delete user accounts',
                'description_ar' => 'إنشاء وتعديل وحذف حسابات المستخدمين'
            ],
            [
                'name' => 'view_users',
                'display_name_en' => 'View Users',
                'display_name_ar' => 'عرض المستخدمين',
                'description_en' => 'View user accounts',
                'description_ar' => 'عرض حسابات المستخدمين'
            ],
            
            // Vendor management permissions
            [
                'name' => 'manage_vendors',
                'display_name_en' => 'Manage Vendors',
                'display_name_ar' => 'إدارة البائعين',
                'description_en' => 'Approve, suspend, and manage vendor accounts',
                'description_ar' => 'الموافقة على وتعليق وإدارة حسابات البائعين'
            ],
            [
                'name' => 'view_vendors',
                'display_name_en' => 'View Vendors',
                'display_name_ar' => 'عرض البائعين',
                'description_en' => 'View vendor accounts',
                'description_ar' => 'عرض حسابات البائعين'
            ],
            
            // Category management permissions
            [
                'name' => 'manage_categories',
                'display_name_en' => 'Manage Categories',
                'display_name_ar' => 'إدارة الفئات',
                'description_en' => 'Create, edit, and delete service categories',
                'description_ar' => 'إنشاء وتعديل وحذف فئات الخدمات'
            ],
            
            // Service management permissions
            [
                'name' => 'manage_services',
                'display_name_en' => 'Manage Services',
                'display_name_ar' => 'إدارة الخدمات',
                'description_en' => 'Create, edit, and delete services',
                'description_ar' => 'إنشاء وتعديل وحذف الخدمات'
            ],
            [
                'name' => 'approve_services',
                'display_name_en' => 'Approve Services',
                'display_name_ar' => 'الموافقة على الخدمات',
                'description_en' => 'Approve or reject service listings',
                'description_ar' => 'الموافقة على أو رفض قوائم الخدمات'
            ],
            
            // Quote management permissions
            [
                'name' => 'manage_quotes',
                'display_name_en' => 'Manage Quotes',
                'display_name_ar' => 'إدارة عروض الأسعار',
                'description_en' => 'Create and respond to quote requests',
                'description_ar' => 'إنشاء والرد على طلبات عروض الأسعار'
            ],
            
            // Order management permissions
            [
                'name' => 'manage_orders',
                'display_name_en' => 'Manage Orders',
                'display_name_ar' => 'إدارة الطلبات',
                'description_en' => 'View and update order status',
                'description_ar' => 'عرض وتحديث حالة الطلب'
            ],
            
            // Review management permissions
            [
                'name' => 'manage_reviews',
                'display_name_en' => 'Manage Reviews',
                'display_name_ar' => 'إدارة التقييمات',
                'description_en' => 'Moderate and manage customer reviews',
                'description_ar' => 'الإشراف على وإدارة تقييمات العملاء'
            ],
            
            // Subscription management permissions
            [
                'name' => 'manage_subscriptions',
                'display_name_en' => 'Manage Subscriptions',
                'display_name_ar' => 'إدارة الاشتراكات',
                'description_en' => 'Create, edit, and manage subscription plans',
                'description_ar' => 'إنشاء وتعديل وإدارة خطط الاشتراك'
            ],
            
            // System settings permissions
            [
                'name' => 'manage_settings',
                'display_name_en' => 'Manage Settings',
                'display_name_ar' => 'إدارة الإعدادات',
                'description_en' => 'Configure system settings',
                'description_ar' => 'تكوين إعدادات النظام'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'admin')->first();
        $vendorRole = Role::where('name', 'vendor')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Admin gets all permissions
        $adminRole->permissions()->attach(Permission::all());

        // Vendor permissions
        $vendorPermissions = [
            'manage_services',
            'manage_quotes',
            'manage_orders',
        ];
        $vendorRole->permissions()->attach(
            Permission::whereIn('name', $vendorPermissions)->get()
        );

        // Customer permissions
        // Customers don't need special permissions as their access is controlled at the controller level
    }
}
