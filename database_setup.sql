DROP TABLE IF EXISTS vendor_responses;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS order_status_history;
DROP TABLE IF EXISTS order_proofs;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS quotes;
DROP TABLE IF EXISTS quote_request_files;
DROP TABLE IF EXISTS quote_requests;
DROP TABLE IF EXISTS service_media;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS subscriptions;
DROP TABLE IF EXISTS subscription_plans;
DROP TABLE IF EXISTS vendor_users;
DROP TABLE IF EXISTS vendors;
DROP TABLE IF EXISTS permission_role;
DROP TABLE IF EXISTS role_user;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS personal_access_tokens;
DROP TABLE IF EXISTS failed_jobs;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create password_reset_tokens table
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Create failed_jobs table
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create personal_access_tokens table
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id)
);

-- Create roles table
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    display_name_en VARCHAR(255) NOT NULL,
    display_name_ar VARCHAR(255) NOT NULL,
    description_en TEXT NULL,
    description_ar TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create permissions table
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    display_name_en VARCHAR(255) NOT NULL,
    display_name_ar VARCHAR(255) NOT NULL,
    description_en TEXT NULL,
    description_ar TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create role_user pivot table
CREATE TABLE role_user (
    role_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (role_id, user_id),
    CONSTRAINT role_user_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE,
    CONSTRAINT role_user_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Create permission_role pivot table
CREATE TABLE permission_role (
    permission_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (permission_id, role_id),
    CONSTRAINT permission_role_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE,
    CONSTRAINT permission_role_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
);

-- Create vendors table
CREATE TABLE vendors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    profile_description_en TEXT NULL,
    profile_description_ar TEXT NULL,
    logo_path VARCHAR(255) NULL,
    location VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT NULL,
    subscription_status ENUM('free', 'premium', 'suspended') NOT NULL DEFAULT 'free',
    subscription_expiry DATE NULL,
    free_quotes_used INT NOT NULL DEFAULT 0,
    free_quotes_limit INT NOT NULL DEFAULT 10,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    rating DECIMAL(3, 2) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT vendors_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Create vendor_users table
CREATE TABLE vendor_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    role VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT vendor_users_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES vendors (id) ON DELETE CASCADE,
    CONSTRAINT vendor_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Create subscription_plans table
CREATE TABLE subscription_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_ar VARCHAR(255) NOT NULL,
    description_en TEXT NOT NULL,
    description_ar TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    duration_months INT NOT NULL,
    features JSON NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create subscriptions table
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id BIGINT UNSIGNED NOT NULL,
    plan_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active',
    payment_method VARCHAR(255) NOT NULL,
    payment_reference VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT subscriptions_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES vendors (id) ON DELETE CASCADE,
    CONSTRAINT subscriptions_plan_id_foreign FOREIGN KEY (plan_id) REFERENCES subscription_plans (id) ON DELETE CASCADE
);

-- Create categories table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    name_en VARCHAR(255) NOT NULL,
    name_ar VARCHAR(255) NOT NULL,
    description_en TEXT NULL,
    description_ar TEXT NULL,
    icon_path VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT categories_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE CASCADE
);

-- Create services table
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255) NOT NULL,
    description_en TEXT NOT NULL,
    description_ar TEXT NOT NULL,
    base_price DECIMAL(10, 2) NOT NULL,
    min_order_qty INT NOT NULL DEFAULT 1,
    production_time_days INT NOT NULL,
    options JSON NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    is_approved BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT services_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES vendors (id) ON DELETE CASCADE,
    CONSTRAINT services_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
);

-- Create service_media table
CREATE TABLE service_media (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id BIGINT UNSIGNED NOT NULL,
    media_type ENUM('image', 'video', 'document') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT service_media_service_id_foreign FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE
);

-- Create quote_requests table
CREATE TABLE quote_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    specifications TEXT NOT NULL,
    delivery_address TEXT NULL,
    delivery_date DATE NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT quote_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT quote_requests_service_id_foreign FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE
);

-- Create quote_request_files table
CREATE TABLE quote_request_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quote_request_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT quote_request_files_quote_request_id_foreign FOREIGN KEY (quote_request_id) REFERENCES quote_requests (id) ON DELETE CASCADE
);

-- Create quotes table
CREATE TABLE quotes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quote_request_id BIGINT UNSIGNED NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    production_time_days INT NOT NULL,
    validity_days INT NOT NULL DEFAULT 7,
    notes TEXT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'expired') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT quotes_quote_request_id_foreign FOREIGN KEY (quote_request_id) REFERENCES quote_requests (id) ON DELETE CASCADE
);

-- Create orders table
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quote_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    vendor_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    order_number VARCHAR(255) NOT NULL UNIQUE,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    specifications TEXT NOT NULL,
    delivery_address TEXT NOT NULL,
    expected_delivery_date DATE NOT NULL,
    actual_delivery_date DATE NULL,
    status ENUM('pending', 'processing', 'proof_approval', 'printing', 'shipping', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(255) NULL,
    payment_reference VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT orders_quote_id_foreign FOREIGN KEY (quote_id) REFERENCES quotes (id) ON DELETE SET NULL,
    CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT orders_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES vendors (id) ON DELETE CASCADE,
    CONSTRAINT orders_service_id_foreign FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE
);

-- Create order_proofs table
CREATE TABLE order_proofs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    version INT NOT NULL DEFAULT 1,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    feedback TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT order_proofs_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
);

-- Create order_status_history table
CREATE TABLE order_status_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(255) NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT order_status_history_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
);

-- Create reviews table
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    vendor_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,
    is_public BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT reviews_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT reviews_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES vendors (id) ON DELETE CASCADE
);

-- Create vendor_responses table
CREATE TABLE vendor_responses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    review_id BIGINT UNSIGNED NOT NULL,
    response TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT vendor_responses_review_id_foreign FOREIGN KEY (review_id) REFERENCES reviews (id) ON DELETE CASCADE
);

-- Create settings table
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create audit_logs table
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(255) NOT NULL,
    entity_id BIGINT UNSIGNED NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT audit_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
);

-- Insert default roles
INSERT INTO roles (name, display_name_en, display_name_ar, description_en, description_ar, created_at, updated_at)
VALUES 
('admin', 'Administrator', 'مدير النظام', 'Full access to all system features', 'وصول كامل إلى جميع ميزات النظام', NOW(), NOW()),
('vendor', 'Vendor', 'بائع', 'Access to vendor features', 'الوصول إلى ميزات البائع', NOW(), NOW()),
('customer', 'Customer', 'عميل', 'Access to customer features', 'الوصول إلى ميزات العميل', NOW(), NOW());

-- Insert default categories
INSERT INTO categories (name_en, name_ar, description_en, description_ar, is_active, display_order, created_at, updated_at)
VALUES 
('Business Cards', 'بطاقات العمل', 'Professional business cards printing services', 'خدمات طباعة بطاقات العمل الاحترافية', TRUE, 1, NOW(), NOW()),
('Brochures', 'كتيبات', 'High-quality brochure printing services', 'خدمات طباعة الكتيبات عالية الجودة', TRUE, 2, NOW(), NOW()),
('Banners', 'لافتات', 'Large format banner printing services', 'خدمات طباعة اللافتات كبيرة الحجم', TRUE, 3, NOW(), NOW()),
('Flyers', 'نشرات إعلانية', 'Promotional flyer printing services', 'خدمات طباعة النشرات الإعلانية الترويجية', TRUE, 4, NOW(), NOW()),
('Posters', 'ملصقات', 'Custom poster printing services', 'خدمات طباعة الملصقات المخصصة', TRUE, 5, NOW(), NOW());

-- Insert default subscription plans
INSERT INTO subscription_plans (name_en, name_ar, description_en, description_ar, price, duration_months, features, is_active, created_at, updated_at)
VALUES 
('Free', 'مجاني', 'Basic vendor features with limited quotes', 'ميزات البائع الأساسية مع عروض أسعار محدودة', 0.00, 0, '{"max_services": 5, "featured_listing": false, "priority_support": false}', TRUE, NOW(), NOW()),
('Premium', 'متميز', 'Full access to all vendor features', 'وصول كامل إلى جميع ميزات البائع', 99.99, 1, '{"max_services": 50, "featured_listing": true, "priority_support": true}', TRUE, NOW(), NOW()),
('Annual', 'سنوي', 'Full access with discounted annual rate', 'وصول كامل بسعر سنوي مخفض', 999.99, 12, '{"max_services": 100, "featured_listing": true, "priority_support": true}', TRUE, NOW(), NOW());

-- Insert default admin user
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
VALUES ('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());

-- Assign admin role to admin user
INSERT INTO role_user (role_id, user_id, created_at, updated_at)
VALUES (1, 1, NOW(), NOW());
