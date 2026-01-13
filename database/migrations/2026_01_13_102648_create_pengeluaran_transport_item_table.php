<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_transport_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transport_id')->constrained('pengeluaran_transport')->onDelete('cascade');
            $table->string('keterangan'); // misal "Tune Up"
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_transport_item');
    }
};
