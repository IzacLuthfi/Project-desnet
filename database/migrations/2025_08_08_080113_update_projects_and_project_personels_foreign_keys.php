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
        Schema::table('projects', function (Blueprint $table) {
            // Jangan dropColumn jika sudah tidak ada
            if (!Schema::hasColumn('projects', 'pm_id')) {
                $table->unsignedBigInteger('pm_id')->after('nilai');
                $table->foreign('pm_id')->references('id')->on('users')->onDelete('cascade');
            }
        });

        Schema::table('project_personels', function (Blueprint $table) {
            // Jangan dropColumn jika sudah tidak ada
            if (!Schema::hasColumn('project_personels', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('project_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
