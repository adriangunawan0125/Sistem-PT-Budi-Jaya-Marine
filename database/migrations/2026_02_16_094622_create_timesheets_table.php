<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('po_masuk_id')
                  ->constrained('po_masuk')
                  ->cascadeOnDelete();

            $table->string('project');
            $table->string('manpower');

            $table->enum('status', ['draft','submitted','approved'])
                  ->default('draft');

            $table->decimal('total_hours', 8, 2)
                  ->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
