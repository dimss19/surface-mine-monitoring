<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One target per material per period (harian/mingguan/bulanan).
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->dropUnique(['material_id']);
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->enum('periode', ['harian', 'mingguan', 'bulanan'])->default('harian')->after('material_id');
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->unique(['material_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->dropUnique(['material_id', 'periode']);
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->unique('material_id');
        });
    }
};
