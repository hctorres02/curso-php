<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Atividade;
use App\Models\Disciplina;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::schema()->create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Atividade::class);
            $table->foreignIdFor(Disciplina::class);
            $table->text('conteudo');
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->dropIfExists('agendamentos');
    }
};
