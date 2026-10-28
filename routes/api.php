<?php

use App\Http\Controllers\Api\SaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\StockMovementController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('suppliers', SupplierController::class);

Route::get('stock-movements', [StockMovementController::class, 'index']);
Route::post('stock-movements', [StockMovementController::class, 'store']);
Route::get('stock-movements/{stockMovement}', [StockMovementController::class, 'show']);

Route::get('sales', [SaleController::class, 'index']);
Route::get('low-stock-products', [ProductController::class, 'lowStock']);
Route::post('sales', [SaleController::class, 'store']);
Route::get('sales/{sale}', [SaleController::class, 'show']);
