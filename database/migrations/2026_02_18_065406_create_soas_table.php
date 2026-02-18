<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soas', function (Blueprint $table) {
            $table->id();
            $table->string('debtor');
            $table->text('address')->nullable();
            $table->date('statement_date');
            $table->string('termin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soas');
    }
};
