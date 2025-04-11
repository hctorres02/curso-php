<?php

use App\Enums\JobStatus;
use App\Enums\JobType;
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
        DB::schema()->create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('callable');
            $table->json('params')->default('[]');
            $table->enum('type', JobType::values())->index();
            $table->enum('status', JobStatus::values())->default(JobStatus::PENDING)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::schema()->dropIfExists('jobs');
    }
};
