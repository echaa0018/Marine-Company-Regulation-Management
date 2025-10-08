#!/bin/bash

# Vercel build script for Laravel application

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Installing NPM dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache

echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "Build completed successfully!"