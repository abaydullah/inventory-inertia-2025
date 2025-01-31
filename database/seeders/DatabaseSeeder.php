<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Product;
use App\Models\Size;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Tenant::create([
            'name' => 'Emart9',
            'sub_domain' => 'emart9',
            'domain' => 'emart9.test',
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'manager'
        ]);

        User::create([
            'name' => 'Store',
            'email' => 'store@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'store',
            'tenant_id' => 1
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'staff',
            'tenant_id' => 1
        ]);

        Category::factory(50)->create();
        Group::factory(100)->create();
        Customer::factory(500)->create();
        Supplier::factory(50)->create();
        Product::factory(500)->create();

        //size//
        Size::create([
            'name' => 'S',
            'tenant_id' => 1
        ]);
        Size::create([
            'name' => 'M',
            'tenant_id' => 1
        ]);
        Size::create([
            'name' => 'L',
            'tenant_id' => 1
        ]);
        Size::create([
            'name' => 'XL',
            'tenant_id' => 1
        ]);
        //color//
        Color::create([
            'name' => 'Black',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'Brown',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'Red',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'Blue',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'Green',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'White',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'Grey',
            'tenant_id' => 1
        ]);
        Color::create([
            'name' => 'Gold',
            'tenant_id' => 1
        ]);
        //unit/
        Unit::create([
            'name' => 'Box',
            'short_name' => 'Box',
            'size' => 12,
            'tenant_id' => 1
        ]);
        Unit::create([
            'name' => 'piece',
            'short_name' => 'piece',
            'size' => 1,
            'tenant_id' => 1
        ]);
        Unit::create([
            'name' => 'Packets',
            'short_name' => 'Packets',
            'size' => 24,
            'tenant_id' => 1
        ]);
        Unit::create([
            'name' => 'Grams',
            'short_name' => 'g',
            'size' => 100,
            'tenant_id' => 1
        ]);
        Account::create([
            'name' => 'Islami Bank',
            'account_number' => '205013202041457',
            'user_id' => 100,
            'tenant_id' => 1
        ]);

    }
}
