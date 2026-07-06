<?php

use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('guests are redirected to login before using the system', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('the login page returns a successful response', function () {
    $response = $this->get('/login');

    $response->assertOk();
});

test('authenticated users can view dashboard and reports', function () {
    $user = User::firstOrCreate([
        'email' => 'test-runner@example.com',
    ], [
        'name' => 'Test Runner',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user)->get('/')->assertOk();
    $this->actingAs($user)->get('/reports/sales')->assertOk();
});

test('authenticated users can view a sales receipt', function () {
    $user = User::firstOrCreate([
        'email' => 'receipt-test@example.com',
    ], [
        'name' => 'Receipt Test',
        'password' => Hash::make('password'),
    ]);

    $sale = Sale::first();

    expect($sale)->not->toBeNull();

    $this->actingAs($user)->get(route('sales.receipt', $sale))->assertOk();
});

test('quick stock item creation generates a sku code', function () {
    $user = User::firstOrCreate([
        'email' => 'stock-quick-item-test@example.com',
    ], [
        'name' => 'Stock Quick Item Test',
        'password' => Hash::make('password'),
    ]);

    $name = 'Quick Modal Item '.uniqid();

    $this->actingAs($user)->post(route('stock-entries.quick-items.store'), [
        'name' => $name,
        'code_auto_generated' => '1',
        'default_purchase_price' => 100,
        'default_selling_price' => 125,
        'low_stock_alert_quantity' => 5,
    ])->assertSessionHasNoErrors();

    $item = Item::where('name', $name)->first();

    expect($item)->not->toBeNull()
        ->and($item->code)->toStartWith('QUICK-MODAL-ITEM');
});

test('stock entries and sales update available item stock', function () {
    $user = User::firstOrCreate([
        'email' => 'stock-flow-test@example.com',
    ], [
        'name' => 'Stock Flow Test',
        'password' => Hash::make('password'),
    ]);

    $item = Item::create([
        'name' => 'Stock Flow Item '.uniqid(),
        'code' => 'STOCK-FLOW-'.uniqid(),
        'default_purchase_price' => 100,
        'default_selling_price' => 150,
        'current_stock' => 0,
        'low_stock_alert_quantity' => 2,
        'is_active' => true,
    ]);

    $this->actingAs($user)->post(route('stock-entries.store'), [
        'date' => now()->toDateString(),
        'item_id' => $item->id,
        'quantity' => 5,
        'price_per_item' => 100,
        'selling_price' => 150,
    ])->assertSessionHasNoErrors();

    expect($item->fresh()->current_stock)->toBe(5);

    $this->actingAs($user)->post(route('sales.store'), [
        'date' => now()->toDateString(),
        'sold_at' => now()->format('Y-m-d H:i:s'),
        'item_id' => $item->id,
        'quantity' => 2,
        'price_per_item' => 150,
        'payment_mode' => 'Cash',
    ])->assertSessionHasNoErrors();

    expect($item->fresh()->current_stock)->toBe(3);
});

test('authenticated users can log expenses', function () {
    $user = User::firstOrCreate([
        'email' => 'expense-test@example.com',
    ], [
        'name' => 'Expense Test',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user)->post(route('expenses.store'), [
        'date' => now()->toDateString(),
        'title' => 'WiFi Bill',
        'category' => 'WiFi',
        'amount' => 1200,
        'payment_mode' => 'Cash',
    ])->assertSessionHasNoErrors();

    $this->assertDatabaseHas('expenses', [
        'title' => 'WiFi Bill',
        'category' => 'WiFi',
        'amount' => 1200,
    ]);
});
