<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->date('tanggal');
            $table->string('item'); // Admin, Setoran, Zona, Repair, dll
            $table->string('keterangan')->nullable();

            $table->decimal('tagihan', 15, 2)->default(0);
            $table->decimal('cicilan', 15, 2)->default(0);

            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
