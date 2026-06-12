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
        Schema::create('shift_reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained(); // kasir
    $table->date('shift_date');
    $table->time('shift_start');
    $table->time('shift_end')->nullable();
    $table->decimal('opening_cash', 12, 2)->default(0);
    $table->decimal('closing_cash', 12, 2)->nullable();
    $table->decimal('total_sales', 12, 2)->default(0);
    $table->integer('total_transactions')->default(0);
    $table->decimal('discrepancy', 12, 2)->nullable(); // selisih
    $table->text('notes')->nullable();
    $table->string('status')->default('open'); // open | closed
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_reports');
    }
};
