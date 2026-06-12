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
        Schema::create('stock_mutations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained();
    $table->foreignId('user_id')->constrained(); // petugas yang input
    $table->enum('type', ['in', 'out', 'opname', 'display']); // jenis mutasi
    $table->integer('quantity_before');
    $table->integer('quantity_change'); // positif = masuk, negatif = keluar
    $table->integer('quantity_after');
    $table->string('reference')->nullable(); // no invoice / no PO
    $table->text('notes')->nullable();
    $table->timestamp('mutation_date');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
