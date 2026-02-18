<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('soa_items', function (Blueprint $table) {
            $table->text('job_details')->nullable()->after('invoice_po_id');
        });
    }

    public function down(): void
    {
        Schema::table('soa_items', function (Blueprint $table) {
            $table->dropColumn('job_details');
        });
    }
};
