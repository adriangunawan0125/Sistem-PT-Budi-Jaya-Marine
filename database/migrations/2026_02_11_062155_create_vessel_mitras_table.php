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
    Schema::create('vessel_mitras', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mitra_marine_id')->constrained()->cascadeOnDelete();
        $table->string('nama_vessel');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('vessel_mitras');
}

};
