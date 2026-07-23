<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {

            $totalPrice = 0;

            $order = Order::create([
                'total_price' => 0,
            ]);

            foreach ($data['items'] as $item) {

                $product = Product::lockForUpdate()
                    ->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        "Insufficient stock for {$product->name}"
                    );
                }

                $subtotal = $product->price * $item['quantity'];

                $totalPrice += $subtotal;

                $product->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            $order->update([
                'total_price' => $totalPrice,
            ]);

            return $order->load('items.product');
        });
    }
}