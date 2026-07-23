<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_and_reduce_stock(): void
    {
        $product = Product::create([
            'name' => 'Gaming Mouse',
            'price' => 300000,
            'stock' => 20,
        ]);

        $response = $this->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'total_price' => 600000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 18,
        ]);
    }
}