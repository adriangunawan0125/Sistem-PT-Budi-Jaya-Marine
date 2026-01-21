<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pemasukans', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->string('deskripsi');
        $table->bigInteger('nominal');
        $table->string('gambar')->nullable(); // bukti transfer / nota
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
