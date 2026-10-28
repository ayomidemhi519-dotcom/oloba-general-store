<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockMovementController extends Controller
{
    public function index(): JsonResponse
    {
        $movements = StockMovement::with('product')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $movements,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in(['stock_in', 'stock_out', 'adjustment'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $movement = DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = $validated['quantity'];

            if ($validated['type'] === 'stock_in') {
                $product->stock_quantity += $quantity;
            } elseif ($validated['type'] === 'stock_out') {
                if ($product->stock_quantity < $quantity) {
                    abort(422, 'Insufficient stock for this stock-out request.');
                }

                $product->stock_quantity -= $quantity;
            } else {
                // For adjustment, quantity is the new stock level.
                $product->stock_quantity = $quantity;
            }

            $product->save();

            return StockMovement::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock movement recorded successfully.',
            'data' => $movement->load('product'),
        ], 201);
    }

    public function show(StockMovement $stockMovement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $stockMovement->load('product'),
        ]);
    }
}
