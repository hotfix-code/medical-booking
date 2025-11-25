<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id');
            $table->uuid('doctor_id')->comment('snapshot');
            $table->uuid('specialty_id');
            $table->uuid('schedule_id');
            $table->uuid('consulting_room_id')->comment('snapshot');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('pending');
            $table->boolean('is_active')->storedAs("(status IN ('pending', 'confirmed'))");
            $table->string('doctor_slot_key')->nullable()->unique('uniq_doctor_slot_key');
            $table->string('room_slot_key')->nullable()->unique('uniq_room_slot_key');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->restrictOnDelete();

            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->restrictOnDelete();

            $table->foreign('specialty_id')
                ->references('id')
                ->on('specialties')
                ->restrictOnDelete();

            $table->foreign('schedule_id')
                ->references('id')
                ->on('schedules')
                ->restrictOnDelete();

            $table->foreign('consulting_room_id')
                ->references('id')
                ->on('consulting_rooms')
                ->restrictOnDelete();

            $table->index(['patient_id', 'appointment_date']);
            $table->index(['doctor_id', 'appointment_date', 'appointment_time']);
            $table->index(['consulting_room_id', 'appointment_date', 'appointment_time']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique('uniq_doctor_slot_active');
            $table->dropUnique('uniq_room_slot_active');

            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['consulting_room_id']);
        });

        Schema::dropIfExists('appointments');
    }
};
