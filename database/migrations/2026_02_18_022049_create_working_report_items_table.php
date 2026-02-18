<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_report_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('working_report_id')
                  ->constrained('working_reports')
                  ->cascadeOnDelete();

            $table->date('work_date');
            $table->text('detail');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_report_items');
    }
};
