# Product Inventory Management System

## Laravel + Vue.js application with Vuetify for managing product inventory

### Features

-   Product listing
-   Excel file upload for inventory updates
-   Automatic quantity adjustment (Buy/Sold)

### Setup Instructions

1. Clone the repository. Run `git clone https://github.com/saifulnizam91/product-inventory.git`
2. Run `composer install`
3. Run `npm install`
4. Create and configure environment `.env` file. Run `cp .env.example .env` then Run `php artisan key:generate`
5. Run migrations and seeders: `php artisan migrate --seed`
6. For Deployment Run `npm run dev`
