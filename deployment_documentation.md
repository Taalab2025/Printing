# Deployment Documentation for Printing Services Marketplace

## Overview
This document provides comprehensive instructions for deploying the Printing Services Marketplace application to a production environment. The marketplace is built using PHP/Laravel with MySQL database and includes features for customers, vendors, and administrators.

## System Requirements

### Server Requirements
- Web server: Apache or Nginx
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer 2.0 or higher
- Node.js 16.0 or higher and NPM
- SSL certificate for secure connections

### PHP Extensions
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- Zip PHP Extension

## Deployment Steps

### 1. Server Preparation

```bash
# Update system packages
sudo apt update
sudo apt upgrade -y

# Install required packages
sudo apt install -y apache2 mysql-server php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-fpm

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and NPM
curl -sL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Database Setup

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE printing_marketplace;
CREATE USER 'printing_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON printing_marketplace.* TO 'printing_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment

```bash
# Clone the repository
git clone https://github.com/your-organization/printing-marketplace.git /var/www/printing-marketplace

# Navigate to project directory
cd /var/www/printing-marketplace

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
npm install
npm run production

# Set proper permissions
sudo chown -R www-data:www-data /var/www/printing-marketplace
sudo chmod -R 755 /var/www/printing-marketplace
sudo chmod -R 775 /var/www/printing-marketplace/storage
sudo chmod -R 775 /var/www/printing-marketplace/bootstrap/cache
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment file with production settings
nano .env
```

Update the following values in the .env file:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=printing_marketplace
DB_USERNAME=printing_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=your-mail-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@your-domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=database

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_DRIVER=file
```

### 5. Database Migration and Seeding

```bash
# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 6. Web Server Configuration

#### Apache Configuration

Create a new virtual host configuration:
```bash
sudo nano /etc/apache2/sites-available/printing-marketplace.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/printing-marketplace/public

    <Directory /var/www/printing-marketplace/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/printing-marketplace-error.log
    CustomLog ${APACHE_LOG_DIR}/printing-marketplace-access.log combined
</VirtualHost>
```

Enable the site and required modules:
```bash
sudo a2ensite printing-marketplace.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx Configuration

Create a new server block configuration:
```bash
sudo nano /etc/nginx/sites-available/printing-marketplace
```

Add the following configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/printing-marketplace/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/printing-marketplace /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

### 7. SSL Configuration

Install Certbot for Let's Encrypt SSL:
```bash
sudo apt install -y certbot

# For Apache
sudo apt install -y python3-certbot-apache
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# For Nginx
sudo apt install -y python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 8. Scheduled Tasks

Set up Laravel's scheduler in crontab:
```bash
sudo crontab -e
```

Add the following line:
```
* * * * * cd /var/www/printing-marketplace && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Queue Worker Setup

Create a systemd service for the Laravel queue worker:
```bash
sudo nano /etc/systemd/system/laravel-worker.service
```

Add the following configuration:
```
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/printing-marketplace/artisan queue:work --sleep=3 --tries=3 --max-time=3600
RestartSec=30

[Install]
WantedBy=multi-user.target
```

Enable and start the service:
```bash
sudo systemctl enable laravel-worker.service
sudo systemctl start laravel-worker.service
```

## Monitoring and Maintenance

### 1. Log Monitoring

Set up log rotation:
```bash
sudo nano /etc/logrotate.d/printing-marketplace
```

Add the following configuration:
```
/var/www/printing-marketplace/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0664 www-data www-data
}
```

### 2. Performance Monitoring

Install and configure New Relic or similar monitoring tool:
```bash
# Example for New Relic
curl -Ls https://download.newrelic.com/install/newrelic-cli/scripts/install.sh | bash
sudo NEW_RELIC_API_KEY=your_api_key NEW_RELIC_ACCOUNT_ID=your_account_id /usr/local/bin/newrelic install
```

### 3. Backup Strategy

Set up automated database backups:
```bash
sudo nano /etc/cron.daily/backup-printing-marketplace
```

Add the following script:
```bash
#!/bin/bash
TIMESTAMP=$(date +"%Y%m%d%H%M%S")
BACKUP_DIR="/var/backups/printing-marketplace"
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u printing_user -p'secure_password' printing_marketplace > $BACKUP_DIR/db-$TIMESTAMP.sql
gzip $BACKUP_DIR/db-$TIMESTAMP.sql

# Application files backup
tar -czf $BACKUP_DIR/app-$TIMESTAMP.tar.gz -C /var/www printing-marketplace

# Keep only last 7 days of backups
find $BACKUP_DIR -name "db-*.sql.gz" -type f -mtime +7 -delete
find $BACKUP_DIR -name "app-*.tar.gz" -type f -mtime +7 -delete
```

Make the script executable:
```bash
sudo chmod +x /etc/cron.daily/backup-printing-marketplace
```

### 4. Security Updates

Set up automatic security updates:
```bash
sudo apt install -y unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

## Troubleshooting

### Common Issues and Solutions

1. **500 Server Error**
   - Check Laravel logs: `tail -f /var/www/printing-marketplace/storage/logs/laravel.log`
   - Verify permissions: `sudo chown -R www-data:www-data /var/www/printing-marketplace/storage`

2. **Database Connection Issues**
   - Verify database credentials in .env file
   - Check MySQL service: `sudo systemctl status mysql`

3. **File Upload Problems**
   - Check PHP upload limits in php.ini
   - Verify storage directory permissions

4. **Performance Issues**
   - Enable Laravel caching: `php artisan config:cache && php artisan route:cache`
   - Optimize Composer: `composer dump-autoload -o`

## Maintenance Procedures

### Regular Maintenance Tasks

1. **Weekly Tasks**
   - Review application logs
   - Check disk space usage
   - Verify backup integrity

2. **Monthly Tasks**
   - Apply security updates
   - Review user activity logs
   - Check for PHP and Laravel updates

3. **Quarterly Tasks**
   - Perform full application backup
   - Review and optimize database performance
   - Update SSL certificates if needed

## Scaling Considerations

### Horizontal Scaling
- Configure load balancing with multiple web servers
- Set up session management with Redis
- Implement a centralized file storage solution

### Vertical Scaling
- Increase server resources (CPU, RAM)
- Optimize database queries and indexes
- Implement caching strategies

## Contact Information

For deployment support, contact:
- Technical Support: support@your-domain.com
- Emergency Contact: +1-234-567-8900
