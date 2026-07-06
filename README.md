# Gandaki Mobile House Stock Management System

Localhost-only Laravel stock/inventory management system for a small shop or personal business.

## Features

- Login/logout authentication
- Dynamic company settings and logo upload
- Dashboard totals, low-stock items, recent activity, and sales chart
- Supplier CRUD
- Item CRUD with images and automatic stock quantity
- Stock entry CRUD with purchase totals
- Sales CRUD with customer details, stock validation, and profit calculation
- Repair/service income CRUD with customer details and profit calculation
- Reports with filters and CSV export

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your MySQL database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gandaki_mobile_house_stock_management_system
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
DB_ENGINE=InnoDB
```

Run the database setup:

```bash
php artisan migrate --seed
php artisan storage:link
```

Start the app:

```bash
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Default Login

- Email: `admin@example.com`
- Password: `password`

## Notes

- Do not edit item stock manually. Stock changes through stock entries and sales.
- Company name, logo, phone, email, address, and footer text are managed from Company Settings.
- CSV exports are available from each report page.
