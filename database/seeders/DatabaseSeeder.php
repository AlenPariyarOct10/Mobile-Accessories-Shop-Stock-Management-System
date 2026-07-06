<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\Item;
use App\Models\RepairService;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            User::updateOrCreate([
                'email' => 'admin@example.com',
            ], [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            CompanySetting::updateOrCreate([
                'id' => 1,
            ], [
                'company_name' => 'Gandaki Mobile House',
                'owner_name' => 'Owner Name',
                'phone' => '9800000000',
                'email' => 'info@gandaki.local',
                'address' => 'Pokhara, Nepal',
                'footer_text' => 'Thank you for your business.',
            ]);

            $supplier = Supplier::create([
                'name' => 'Main Mobile Supplier',
                'phone' => '9811111111',
                'address' => 'New Road, Pokhara',
                'email' => 'supplier@example.com',
                'notes' => 'Primary supplier for phones and accessories.',
            ]);

            $items = collect([
                ['name' => 'Tempered Glass', 'code' => 'TG-001', 'default_purchase_price' => 80, 'default_selling_price' => 150, 'low_stock_alert_quantity' => 10],
                ['name' => 'USB-C Charger', 'code' => 'CHR-USB-C', 'default_purchase_price' => 450, 'default_selling_price' => 700, 'low_stock_alert_quantity' => 5],
                ['name' => 'Earphones', 'code' => 'EAR-001', 'default_purchase_price' => 300, 'default_selling_price' => 550, 'low_stock_alert_quantity' => 5],
            ])->map(fn (array $data) => Item::create($data + ['current_stock' => 0, 'is_active' => true]));

            foreach ($items as $item) {
                $quantity = $item->code === 'TG-001' ? 30 : 12;
                StockEntry::create([
                    'date' => now()->toDateString(),
                    'item_id' => $item->id,
                    'supplier_id' => $supplier->id,
                    'quantity' => $quantity,
                    'price_per_item' => $item->default_purchase_price,
                    'selling_price' => $item->default_selling_price,
                    'total_purchase_price' => $quantity * $item->default_purchase_price,
                    'notes' => 'Opening stock',
                ]);
                $item->increment('current_stock', $quantity);
            }

            $glass = $items->firstWhere('code', 'TG-001');
            Sale::create([
                'date' => now()->toDateString(),
                'sold_at' => now(),
                'item_id' => $glass->id,
                'quantity' => 2,
                'price_per_item' => 150,
                'total_sales_price' => 300,
                'estimated_purchase_cost' => 160,
                'profit' => 140,
                'payment_mode' => 'Cash',
                'customer_name' => 'Sample Customer',
                'customer_phone' => '9822222222',
                'customer_address' => 'Lakeside',
                'notes' => 'Sample sale',
            ]);
            $glass->decrement('current_stock', 2);

            RepairService::create([
                'date' => now()->toDateString(),
                'serviced_at' => now(),
                'customer_name' => 'Repair Customer',
                'customer_phone' => '9833333333',
                'customer_address' => 'Mahendrapool',
                'service_type' => 'Phone Repair',
                'description' => 'Charging port cleaning and service',
                'cost_price' => 100,
                'charged_price' => 500,
                'profit' => 400,
                'payment_mode' => 'eSewa',
                'notes' => 'Sample repair/service record',
            ]);
        });
    }
}
