<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::schema()->create('periodos', function (Blueprint $table) {
            $table->id();
            $table->string('ano');
            $table->string('semestre');
            $table->timestamps();
            $table->unique(['ano', 'semestre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->dropIfExists('periodos');
    }
};
