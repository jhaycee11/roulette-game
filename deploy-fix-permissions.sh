#!/bin/bash
# Deployment script to fix next-to-win permissions
# Run this after git pull on your deployment server

echo "=== FIXING NEXT-TO-WIN PERMISSIONS ==="

# Navigate to project directory
cd "$(dirname "$0")"

# Create storage directory if it doesn't exist
mkdir -p storage/app
mkdir -p public/storage/save

# Set proper permissions for Laravel storage
chmod -R 755 storage/
chmod -R 755 public/storage/

# Create nexttowin.json if it doesn't exist
if [ ! -f "storage/app/nexttowin.json" ]; then
    echo "[]" > storage/app/nexttowin.json
    echo "Created storage/app/nexttowin.json"
fi

# Set permissions for the nexttowin.json file
chmod 644 storage/app/nexttowin.json

# Set ownership (adjust based on your web server)
# Uncomment the line that matches your setup:

# For Apache:
# chown -R www-data:www-data storage/ public/storage/

# For Nginx:
# chown -R nginx:nginx storage/ public/storage/

# For other setups, use current user:
chown -R $(whoami):$(whoami) storage/ public/storage/

echo "=== PERMISSIONS FIXED ==="
echo "Next-to-win file location: storage/app/nexttowin.json"
echo "Public storage location: public/storage/save/"
