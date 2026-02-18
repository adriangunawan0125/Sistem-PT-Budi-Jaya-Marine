<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_report_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('working_report_item_id')
                  ->constrained('working_report_items')
                  ->cascadeOnDelete();

            $table->string('image_path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_report_images');
    }
};
