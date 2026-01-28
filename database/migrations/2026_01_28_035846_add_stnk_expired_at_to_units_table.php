<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('units', function (Blueprint $table) {
        $table->date('stnk_expired_at')
              ->nullable()
              ->after('status');
    });
}

public function down(): void
{
    Schema::table('units', function (Blueprint $table) {
        $table->dropColumn('stnk_expired_at');
    });
}

};
