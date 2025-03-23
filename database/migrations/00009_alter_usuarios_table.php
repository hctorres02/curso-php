<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::schema()->table('usuarios', function (Blueprint $table) {
            $table->json('permissions')->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->table('usuarios', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
