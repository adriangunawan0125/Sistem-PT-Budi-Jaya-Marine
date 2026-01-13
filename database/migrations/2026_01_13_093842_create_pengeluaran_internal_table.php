<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_internal', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');          // tanggal pengeluaran
            $table->string('deskripsi');      // deskripsi pengeluaran
            $table->decimal('nominal', 15, 2); // nominal pengeluaran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_internal');
    }
};
