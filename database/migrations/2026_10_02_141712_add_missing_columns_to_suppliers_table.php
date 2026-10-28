
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('name', 150);
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropColumn([
                'name',
                'phone',
                'email',
                'address',
            ]);
        });
    }
};
