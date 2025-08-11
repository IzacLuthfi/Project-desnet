<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('project_personels', function (Blueprint $table) {
            if (!Schema::hasColumn('project_personels', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('project_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_personels', function (Blueprint $table) {
            //
        });
    }
};
