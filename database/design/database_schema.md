# Printing Services Marketplace - Database Schema Design

## Overview

This document outlines the database schema design for the Printing Services Marketplace platform. The schema is designed to support all the requirements identified in the business requirements analysis, including user roles (Customer, Vendor, Admin), multilingual content (English/Arabic), quotation system, order management, and the freemium subscription model for vendors.

## Database Tables

### User Management Tables

#### 1. users
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255)
- `email` - varchar(255) UNIQUE
- `email_verified_at` - timestamp NULL
- `password` - varchar(255)
- `role` - enum('customer', 'vendor', 'admin')
- `phone` - varchar(20) NULL
- `company` - varchar(255) NULL
- `language_pref` - enum('en', 'ar') DEFAULT 'en'
- `remember_token` - varchar(100) NULL
- `created_at` - timestamp
- `updated_at` - timestamp

#### 2. roles
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255) UNIQUE
- `display_name_en` - varchar(255)
- `display_name_ar` - varchar(255)
- `description_en` - text NULL
- `description_ar` - text NULL
- `created_at` - timestamp
- `updated_at` - timestamp

#### 3. permissions
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name` - varchar(255) UNIQUE
- `display_name_en` - varchar(255)
- `display_name_ar` - varchar(255)
- `description_en` - text NULL
- `description_ar` - text NULL
- `created_at` - timestamp
- `updated_at` - timestamp

#### 4. role_user (Pivot)
- `role_id` - bigint(20) unsigned FOREIGN KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `created_at` - timestamp
- `updated_at` - timestamp

#### 5. permission_role (Pivot)
- `permission_id` - bigint(20) unsigned FOREIGN KEY
- `role_id` - bigint(20) unsigned FOREIGN KEY
- `created_at` - timestamp
- `updated_at` - timestamp

### Vendor Management Tables

#### 6. vendors
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `company_name` - varchar(255)
- `profile_description_en` - text NULL
- `profile_description_ar` - text NULL
- `logo_path` - varchar(255) NULL
- `location` - varchar(255)
- `city` - varchar(100)
- `address` - text NULL
- `subscription_status` - enum('free', 'premium', 'suspended')
- `subscription_expiry` - date NULL
- `free_quotes_used` - int DEFAULT 0
- `free_quotes_limit` - int DEFAULT 10
- `is_featured` - boolean DEFAULT false
- `rating` - decimal(3,2) NULL
- `created_at` - timestamp
- `updated_at` - timestamp

#### 7. vendor_users (Pivot for multiple staff per vendor)
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `vendor_id` - bigint(20) unsigned FOREIGN KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `role` - enum('owner', 'staff', 'manager')
- `created_at` - timestamp
- `updated_at` - timestamp

#### 8. subscription_plans
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `name_en` - varchar(255)
- `name_ar` - varchar(255)
- `description_en` - text
- `description_ar` - text
- `price` - decimal(10,2)
- `duration_months` - int
- `features` - json
- `is_active` - boolean DEFAULT true
- `created_at` - timestamp
- `updated_at` - timestamp

#### 9. subscriptions
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `vendor_id` - bigint(20) unsigned FOREIGN KEY
- `plan_id` - bigint(20) unsigned FOREIGN KEY
- `start_date` - date
- `end_date` - date
- `status` - enum('active', 'expired', 'cancelled')
- `payment_status` - enum('paid', 'pending', 'failed')
- `created_at` - timestamp
- `updated_at` - timestamp

### Service Management Tables

#### 10. categories
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `parent_id` - bigint(20) unsigned NULL FOREIGN KEY (self-referencing)
- `name_en` - varchar(255)
- `name_ar` - varchar(255)
- `description_en` - text NULL
- `description_ar` - text NULL
- `icon` - varchar(255) NULL
- `slug` - varchar(255) UNIQUE
- `is_active` - boolean DEFAULT true
- `created_at` - timestamp
- `updated_at` - timestamp

#### 11. services
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `vendor_id` - bigint(20) unsigned FOREIGN KEY
- `category_id` - bigint(20) unsigned FOREIGN KEY
- `title_en` - varchar(255)
- `title_ar` - varchar(255)
- `description_en` - text
- `description_ar` - text
- `base_price` - decimal(10,2)
- `min_order_qty` - int DEFAULT 1
- `production_time_days` - int
- `options` - json NULL
- `is_active` - boolean DEFAULT true
- `is_approved` - boolean DEFAULT false
- `created_at` - timestamp
- `updated_at` - timestamp

#### 12. service_media
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `service_id` - bigint(20) unsigned FOREIGN KEY
- `file_path` - varchar(255)
- `type` - enum('image', 'video')
- `is_primary` - boolean DEFAULT false
- `sort_order` - int DEFAULT 0
- `created_at` - timestamp
- `updated_at` - timestamp

### Quotation System Tables

#### 13. quote_requests
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `service_id` - bigint(20) unsigned FOREIGN KEY
- `category_id` - bigint(20) unsigned FOREIGN KEY
- `title` - varchar(255)
- `requirements` - text
- `quantity` - int
- `delivery_date` - date NULL
- `status` - enum('pending', 'in_progress', 'completed', 'cancelled')
- `created_at` - timestamp
- `updated_at` - timestamp

#### 14. quote_request_files
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `quote_request_id` - bigint(20) unsigned FOREIGN KEY
- `file_path` - varchar(255)
- `file_name` - varchar(255)
- `file_size` - int
- `file_type` - varchar(100)
- `created_at` - timestamp
- `updated_at` - timestamp

#### 15. quotes
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `quote_request_id` - bigint(20) unsigned FOREIGN KEY
- `vendor_id` - bigint(20) unsigned FOREIGN KEY
- `price` - decimal(10,2)
- `delivery_time_days` - int
- `notes` - text NULL
- `status` - enum('pending', 'accepted', 'rejected', 'expired')
- `valid_until` - date
- `created_at` - timestamp
- `updated_at` - timestamp

### Order Management Tables

#### 16. orders
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `quote_id` - bigint(20) unsigned FOREIGN KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `vendor_id` - bigint(20) unsigned FOREIGN KEY
- `order_number` - varchar(50) UNIQUE
- `total_amount` - decimal(10,2)
- `status` - enum('pending', 'in_production', 'ready_to_ship', 'delivered', 'cancelled')
- `payment_status` - enum('pending', 'paid', 'refunded', 'failed')
- `payment_method` - varchar(100) NULL
- `delivery_address` - text
- `delivery_notes` - text NULL
- `expected_delivery_date` - date
- `actual_delivery_date` - date NULL
- `created_at` - timestamp
- `updated_at` - timestamp

#### 17. order_proofs
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `order_id` - bigint(20) unsigned FOREIGN KEY
- `file_path` - varchar(255)
- `notes` - text NULL
- `status` - enum('pending', 'approved', 'rejected')
- `created_at` - timestamp
- `updated_at` - timestamp

#### 18. order_status_history
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `order_id` - bigint(20) unsigned FOREIGN KEY
- `status` - varchar(100)
- `notes` - text NULL
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `created_at` - timestamp
- `updated_at` - timestamp

### Review and Rating System Tables

#### 19. reviews
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `order_id` - bigint(20) unsigned FOREIGN KEY
- `user_id` - bigint(20) unsigned FOREIGN KEY
- `vendor_id` - bigint(20) unsigned FOREIGN KEY
- `rating` - tinyint
- `comment` - text NULL
- `is_approved` - boolean DEFAULT true
- `created_at` - timestamp
- `updated_at` - timestamp

#### 20. vendor_responses
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `review_id` - bigint(20) unsigned FOREIGN KEY
- `response` - text
- `created_at` - timestamp
- `updated_at` - timestamp

### System Management Tables

#### 21. settings
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `key` - varchar(255) UNIQUE
- `value` - text
- `group` - varchar(100) NULL
- `created_at` - timestamp
- `updated_at` - timestamp

#### 22. audit_logs
- `id` - bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
- `user_id` - bigint(20) unsigned NULL FOREIGN KEY
- `action` - varchar(255)
- `model` - varchar(255)
- `model_id` - bigint(20) unsigned NULL
- `old_values` - json NULL
- `new_values` - json NULL
- `ip_address` - varchar(45) NULL
- `user_agent` - varchar(255) NULL
- `created_at` - timestamp
- `updated_at` - timestamp

## Relationships

1. **User Management**
   - Users belong to many Roles (many-to-many)
   - Roles have many Permissions (many-to-many)

2. **Vendor Management**
   - Users can be associated with Vendors (one-to-many)
   - Vendors can have multiple staff Users (many-to-many)
   - Vendors have many Subscriptions (one-to-many)
   - Subscription Plans have many Subscriptions (one-to-many)

3. **Service Management**
   - Categories can have parent Categories (self-referencing one-to-many)
   - Categories have many Services (one-to-many)
   - Vendors have many Services (one-to-many)
   - Services have many Service Media (one-to-many)

4. **Quotation System**
   - Users have many Quote Requests (one-to-many)
   - Services have many Quote Requests (one-to-many)
   - Categories have many Quote Requests (one-to-many)
   - Quote Requests have many Quote Request Files (one-to-many)
   - Quote Requests have many Quotes (one-to-many)
   - Vendors have many Quotes (one-to-many)

5. **Order Management**
   - Quotes have one Order (one-to-one)
   - Users have many Orders (one-to-many)
   - Vendors have many Orders (one-to-many)
   - Orders have many Order Proofs (one-to-many)
   - Orders have many Order Status History entries (one-to-many)

6. **Review System**
   - Orders have one Review (one-to-one)
   - Users have many Reviews (one-to-many)
   - Vendors have many Reviews (one-to-many)
   - Reviews have one Vendor Response (one-to-one)

## Indexes

To optimize query performance, the following indexes will be created:

1. Foreign key indexes on all relationship columns
2. Composite indexes on frequently queried combinations
3. Full-text indexes on searchable content fields

## Considerations for Multilingual Support

- All user-facing content fields have both English (_en) and Arabic (_ar) versions
- The user's language preference is stored in the users table
- The application will serve content based on the user's language preference

## Data Security Considerations

- Passwords are stored as hashes (using Laravel's built-in hashing)
- Sensitive operations are logged in the audit_logs table
- File paths are stored rather than actual files in the database
- Payment information is not stored directly in the database

## Migration Strategy

The database schema will be implemented using Laravel migrations, which will allow for:

1. Version control of database schema changes
2. Easy rollback of changes if needed
3. Consistent database structure across all environments
4. Seeding of initial data (roles, permissions, categories, etc.)
