<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Target is set once per material (applies for days/weeks/months), not per date.
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->dropUnique(['material_id', 'tanggal']);
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->unique('material_id');
        });
    }

    public function down(): void
    {
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->dropUnique(['material_id']);
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->date('tanggal')->nullable();
        });
        Schema::table('daily_targets', function (Blueprint $table) {
            $table->unique(['material_id', 'tanggal']);
        });
    }
};
