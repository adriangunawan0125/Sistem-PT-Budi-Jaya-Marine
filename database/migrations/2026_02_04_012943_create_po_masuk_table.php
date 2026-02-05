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
       Schema::create('po_masuk', function (Blueprint $table) {
    $table->id();
    $table->string('nama_perusahaan');
    $table->text('alamat')->nullable();
    $table->date('tanggal_po');
    $table->string('no_po_klien');
    $table->string('vessel')->nullable();
    $table->decimal('total_jual', 15, 2)->default(0);
    $table->enum('status', ['draft', 'approved', 'processing', 'delivered', 'closed'])->default('draft');
    $table->decimal('margin', 15, 2)->nullable();
    $table->enum('margin_status', ['profit', 'loss', 'break_even'])->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_masuk');
    }
};
