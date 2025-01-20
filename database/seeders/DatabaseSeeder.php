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
            'role' => 'store'
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'staff'
        ]);

        Category::factory(50)->create();
        Group::factory(100)->create();
        Customer::factory(500)->create();
        Supplier::factory(50)->create();
        Product::factory(500)->create();

        //size//
        Size::create([
            'name' => 'S',
        ]);
        Size::create([
            'name' => 'M',
        ]);
        Size::create([
            'name' => 'L',
        ]);
        Size::create([
            'name' => 'XL',
        ]);
        //color//
        Color::create([
            'name' => 'Black',
        ]);
        Color::create([
            'name' => 'Brown',
        ]);
        Color::create([
            'name' => 'Red',
        ]);
        Color::create([
            'name' => 'Blue',
        ]);
        Color::create([
            'name' => 'Green',
        ]);
        Color::create([
            'name' => 'White',
        ]);
        Color::create([
            'name' => 'Grey',
        ]);
        Color::create([
            'name' => 'Gold',
        ]);
        //unit/
        Unit::create([
            'name' => 'Box',
            'short_name' => 'Box',
            'size' => 12
        ]);
        Unit::create([
            'name' => 'piece',
            'short_name' => 'piece',
            'size' => 1
        ]);
        Unit::create([
            'name' => 'Packets',
            'short_name' => 'Packets',
            'size' => 24
        ]);
        Unit::create([
            'name' => 'Grams',
            'short_name' => 'g',
            'size' => 100
        ]);
        Account::create([
            'name' => 'Islami Bank',
            'account_number' => '205013202041457',
            'user_id' => 100
        ]);

    }
}
