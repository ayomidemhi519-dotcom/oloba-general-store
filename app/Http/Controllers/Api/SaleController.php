<?php

namespace App\Http\Controllers\Api;

use App\Models\StockMovement;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    public function index(): JsonResponse
    {
        $sales = Sale::with('items.product')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Sales retrieved successfully.',
            'data' => $sales,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'discount' => ['sometimes', 'numeric', 'min:0'],
            'payment_method' => [
                'sometimes',
                Rule::in(['cash', 'transfer', 'card']),
            ],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $saleItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = $item['quantity'];

                if ($product->stock_quantity < $quantity) {
                    abort(
                        422,
                        "Insufficient stock for {$product->name}."
                    );
                }

                $unitPrice = (float) $product->selling_price;
                $itemSubtotal = $unitPrice * $quantity;

                $subtotal += $itemSubtotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                ];

                $product->stock_quantity -= $quantity;
                $product->save();
                
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'stock_out',
                    'quantity' => $quantity,
                    'notes' => 'Stock deducted from sale.',
                ]);
            }

            $discount = (float) ($validated['discount'] ?? 0);

            if ($discount > $subtotal) {
                abort(422, 'Discount cannot be greater than the subtotal.');
            }

            $total = $subtotal - $discount;

            $sale = Sale::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($saleItems as $item) {
                $sale->items()->create($item);
            }

            return $sale;
        });

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully.',
            'data' => $sale->load('items.product'),
        ], 201);
    }

    public function show(Sale $sale): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $sale->load('items.product'),
        ]);
    }
}
