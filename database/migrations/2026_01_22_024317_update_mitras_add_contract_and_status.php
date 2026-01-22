<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            // unit_id boleh NULL (untuk ex mitra)
            $table->unsignedBigInteger('unit_id')->nullable()->change();

            // kolom kontrak
            $table->date('kontrak_mulai')->nullable()->after('no_hp');
            $table->date('kontrak_berakhir')->nullable()->after('kontrak_mulai');

            // status mitra
            $table->enum('status', ['aktif', 'berakhir'])
                  ->default('aktif')
                  ->after('kontrak_berakhir');
        });
    }

    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            // balikin ke kondisi awal
            $table->unsignedBigInteger('unit_id')->nullable(false)->change();

            $table->dropColumn([
                'kontrak_mulai',
                'kontrak_berakhir',
                'status'
            ]);
        });
    }
};
