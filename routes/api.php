<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\StockMovementController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });
});

// Categories
Route::apiResource('categories', CategoryController::class);

// Products
Route::apiResource('products', ProductController::class);
Route::get('/products/barcode/{barcode}', [ProductController::class, 'findByBarcode']);
Route::get('/low-stock-products', [ProductController::class, 'lowStock']);

// Suppliers
Route::apiResource('suppliers', SupplierController::class);

// Stock Movements
Route::get('stock-movements', [StockMovementController::class, 'index']);
Route::post('stock-movements', [StockMovementController::class, 'store']);
Route::get('stock-movements/{stockMovement}', [StockMovementController::class, 'show']);

// Sales
Route::get('sales', [SaleController::class, 'index']);
Route::post('sales', [SaleController::class, 'store']);
Route::get('sales/{sale}', [SaleController::class, 'show']);
