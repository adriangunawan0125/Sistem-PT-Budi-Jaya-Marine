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
       Schema::create('jaminan_mitras', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mitra_id')
          ->constrained('mitras')
          ->onDelete('cascade');

    $table->string('jaminan');
    $table->string('gambar_1')->nullable();
    $table->string('gambar_2')->nullable();
    $table->string('gambar_3')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaminan_mitras');
    }
};
