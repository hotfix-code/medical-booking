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
        Schema::create('doctor_specialty', function (Blueprint $table) {
            $table->uuid('doctor_id');
            $table->uuid('specialty_id');
            $table->timestamps();

            $table->primary(['doctor_id', 'specialty_id']);

            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->onDelete('cascade');

            $table->foreign('specialty_id')
                ->references('id')
                ->on('specialties')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_specialty', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['specialty_id']);
        });
        Schema::dropIfExists('doctor_specialty');
    }
};
