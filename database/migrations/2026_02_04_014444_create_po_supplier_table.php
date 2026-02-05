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
       Schema::create('po_supplier', function (Blueprint $table) {
    $table->id();
    $table->foreignId('po_masuk_id')->constrained('po_masuk')->cascadeOnDelete();
    $table->string('nama_perusahaan');
    $table->text('alamat')->nullable();
    $table->date('tanggal_po');
    $table->string('no_po_internal');
    $table->decimal('total_beli', 15, 2)->default(0);
    $table->enum('status', ['draft', 'approved', 'cancelled'])->default('draft');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_supplier');
    }
};
