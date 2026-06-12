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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('barcode')->unique();
    $table->string('name');
    $table->string('category')->nullable();
    $table->decimal('price', 12, 2);
    $table->decimal('cost_price', 12, 2)->default(0); // harga beli
    $table->integer('stock')->default(0);
    $table->integer('stock_minimum')->default(5); // batas peringatan
    $table->string('unit')->default('pcs'); // satuan (pcs, kg, liter)
    $table->string('supplier')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
