<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('opname_code')->unique();
    $table->date('opname_date');
    $table->string('status')->default('draft'); // draft | submitted | approved
    $table->text('notes')->nullable();
    $table->timestamps();
});

Schema::create('stock_opname_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('stock_opname_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained();
    $table->integer('system_stock'); // stok di sistem
    $table->integer('physical_stock'); // stok fisik hasil hitung
    $table->integer('difference'); // selisih
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
