<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('po_masuk_id')
                  ->constrained('po_masuk')
                  ->cascadeOnDelete();

            $table->string('project');
            $table->string('place')->nullable();
            $table->string('periode');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_reports');
    }
};
