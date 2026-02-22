#!/bin/bash

# Start Laravel development server with custom PHP settings
php -d upload_max_filesize=10M -d post_max_size=20M -d memory_limit=256M artisan serve
