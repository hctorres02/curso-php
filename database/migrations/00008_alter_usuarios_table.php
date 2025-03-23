<?php

use App\Enums\Role;
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
            $table->enum('role', Role::values())->default(Role::VISITANTE)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->table('usuarios', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
