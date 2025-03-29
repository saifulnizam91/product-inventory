<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['product_id' => 1257, 'category' => 'Laptop', 'brand' => 'Apple', 'model' => 'Macbook Pro', 'quantity' => 10],
            ['product_id' => 1277, 'category' => 'Laptop', 'brand' => 'Dell', 'model' => 'XPS', 'quantity' => 25],
            ['product_id' => 2297, 'category' => 'Phone', 'brand' => 'Apple', 'model' => 'iPhone 15', 'quantity' => 6],
            ['product_id' => 2376, 'category' => 'Phone', 'brand' => 'Samsung', 'model' => 'Galaxy S25', 'quantity' => 12],
            ['product_id' => 3356, 'category' => 'Mouse', 'brand' => 'Razer', 'model' => 'Basilisk', 'quantity' => 55],
            ['product_id' => 3567, 'category' => 'Mouse', 'brand' => 'Logitech', 'model' => 'MX Master 3s', 'quantity' => 43],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
