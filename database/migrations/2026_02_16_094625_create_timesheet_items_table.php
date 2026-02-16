<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheet_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('timesheet_id')
                  ->constrained('timesheets')
                  ->cascadeOnDelete();

            $table->date('work_date');
            $table->string('day')->nullable();

            $table->time('time_start');
            $table->time('time_end');

            $table->decimal('hours', 5, 2)
                  ->default(0.00);

            $table->string('manpower')->nullable();

            $table->text('kind_of_work');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheet_items');
    }
};
