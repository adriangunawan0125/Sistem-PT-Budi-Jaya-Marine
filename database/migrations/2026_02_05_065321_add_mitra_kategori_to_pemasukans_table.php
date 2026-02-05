<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. tambah kolom nullable dulu
        Schema::table('pemasukans', function (Blueprint $table) {
            $table->unsignedBigInteger('mitra_id')->nullable()->after('tanggal');
            $table->enum('kategori', ['setoran','cicilan','deposit'])
                  ->default('setoran')
                  ->after('mitra_id');
        });

        // 2. isi semua data lama pakai mitra pertama
        $mitra = DB::table('mitras')->orderBy('id')->first();

        if ($mitra) {
            DB::table('pemasukans')->update([
                'mitra_id' => $mitra->id
            ]);
        }

        // 3. jadikan NOT NULL
        Schema::table('pemasukans', function (Blueprint $table) {
            $table->unsignedBigInteger('mitra_id')->nullable(false)->change();
        });

        // 4. pasang foreign key
        Schema::table('pemasukans', function (Blueprint $table) {
            $table->foreign('mitra_id')
                  ->references('id')
                  ->on('mitras')
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('pemasukans', function (Blueprint $table) {
            $table->dropForeign(['mitra_id']);
            $table->dropColumn(['mitra_id','kategori']);
        });
    }
};
