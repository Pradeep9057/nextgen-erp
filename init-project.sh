#!/bin/bash
set -e

# Install Laravel via Composer inside the container
composer create-project laravel/laravel .

# Install React/TypeScript via Vite (Laravel default)
npm install
npm install -D typescript @types/react @types/react-dom

# Create basic folder structure for the Modular Monolith
mkdir -p app/Core/Auth app/Core/Permissions app/Core/Audit
mkdir -p app/Modules/CRM/Services app/Modules/CRM/Models app/Modules/CRM/Http app/Modules/CRM/Tests
mkdir -p app/Modules/Inventory/Services app/Modules/Inventory/Models app/Modules/Inventory/Http app/Modules/Inventory/Tests
mkdir -p app/Shared

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .

echo "Laravel project initialized successfully inside the container."
