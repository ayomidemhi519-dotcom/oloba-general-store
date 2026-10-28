<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->enum('type', [
                'stock_in',
                'stock_out',
                'adjustment'
            ]);

            $table->unsignedInteger('quantity');

            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['product_id']);

            $table->dropColumn([
                'product_id',
                'type',
                'quantity',
                'notes',
            ]);
        });
    }
};
