<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_pajak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // relasi ke units
            $table->date('tanggal');          // tanggal bayar pajak
            $table->string('deskripsi');      // deskripsi, misal "Pajak Tahunan"
            $table->decimal('nominal', 15, 2); // jumlah pajak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_pajak');
    }
};
