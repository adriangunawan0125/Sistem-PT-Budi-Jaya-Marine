<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('po_masuk', function (Blueprint $table) {

            // ✅ Drop kolom lama kalau masih ada
            if (Schema::hasColumn('po_masuk', 'nama_perusahaan')) {
                $table->dropColumn('nama_perusahaan');
            }

            if (Schema::hasColumn('po_masuk', 'vessel')) {
                $table->dropColumn('vessel');
            }

            // ✅ Tambah FK kalau belum ada
            if (!Schema::hasColumn('po_masuk', 'mitra_marine_id')) {
                $table->unsignedBigInteger('mitra_marine_id')->after('id');
            }

            if (!Schema::hasColumn('po_masuk', 'vessel_mitra_id')) {
                $table->unsignedBigInteger('vessel_mitra_id')->after('mitra_marine_id');
            }

        });

        // ✅ Tambah foreign key di luar block (lebih aman)
        Schema::table('po_masuk', function (Blueprint $table) {

            if (!Schema::hasColumn('po_masuk', 'mitra_marine_id')) return;

            $table->foreign('mitra_marine_id')
                ->references('id')
                ->on('mitra_marines')
                ->onDelete('cascade');

            $table->foreign('vessel_mitra_id')
                ->references('id')
                ->on('vessel_mitras')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('po_masuk', function (Blueprint $table) {

            // Drop FK dulu
            $table->dropForeign(['mitra_marine_id']);
            $table->dropForeign(['vessel_mitra_id']);

            // Drop kolom FK
            $table->dropColumn(['mitra_marine_id','vessel_mitra_id']);

            // Balikin kolom lama
            $table->string('nama_perusahaan')->nullable();
            $table->string('vessel')->nullable();
        });
    }
};
