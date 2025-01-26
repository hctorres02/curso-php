<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Periodo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::schema()->create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Periodo::class);
            $table->string('nome');
            $table->string('cor');
            $table->timestamps();
            $table->unique(['nome', 'periodo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->dropIfExists('disciplinas');
    }
};
