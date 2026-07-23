<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with('items.product')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ], 200);
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {

            $order = DB::transaction(function () use ($validated) {

                $totalPrice = 0;

                // Create order
                $order = Order::create([
                    'total_price' => 0,
                ]);

                foreach ($validated['items'] as $item) {

                    // Lock product row
                    $product = Product::lockForUpdate()
                        ->findOrFail($item['product_id']);

                    // Check stock
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception(
                            "Insufficient stock for product: {$product->name}"
                        );
                    }

                    // Calculate subtotal
                    $subtotal = $product->price * $item['quantity'];

                    $totalPrice += $subtotal;

                    // Reduce stock
                    $product->decrement('stock', $item['quantity']);

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }

                // Update total price
                $order->update([
                    'total_price' => $totalPrice,
                ]);

                return $order->load('items.product');

            });

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'data' => $order,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load('items.product');

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $order,
        ], 200);
    }

    /**
     * Update order is not allowed.
     */
    public function update()
    {
        abort(405, 'Method Not Allowed');
    }

    /**
     * Delete order is not allowed.
     */
    public function destroy()
    {
        abort(405, 'Method Not Allowed');
    }
}