<?php

use App\Models\Agendamento;
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
        DB::schema()->create('anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Agendamento::class);
            $table->string('nome_original');
            $table->string('caminho');
            $table->string('tipo');
            $table->string('extensao');
            $table->string('tamanho');
            $table->timestamps();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->dropIfExists('anexos');
    }
};
