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
        DB::schema()->create('sessions', function (Blueprint $table) {
            $table->text('sess_id')->primary();
            $table->text('sess_data');
            $table->integer('sess_lifetime')->index();
            $table->integer('sess_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->dropIfExists('sessions');
    }
};
