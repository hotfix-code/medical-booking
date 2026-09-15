<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->assertNoDoctorCollisions();

        $this->rehash(fn (object $appointment) => sprintf(
            '%s-%s-%s',
            $appointment->doctor_id,
            $appointment->appointment_date,
            $appointment->appointment_time
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->rehash(fn (object $appointment) => sprintf(
            '%s-%s-%s-%s',
            $appointment->doctor_id,
            $appointment->specialty_id,
            $appointment->appointment_date,
            $appointment->appointment_time
        ));
    }

    /**
     * Active appointments that share doctor, date and time would get the same
     * doctor_slot_key once rehashed, violating uniq_doctor_slot_key.
     */
    private function assertNoDoctorCollisions(): void
    {
        $collisions = DB::table('appointments')
            ->select('doctor_id', 'appointment_date', 'appointment_time')
            ->whereIn('status', ['pending', 'confirmed'])
            ->groupBy('doctor_id', 'appointment_date', 'appointment_time')
            ->havingRaw('count(*) > 1')
            ->get();

        if ($collisions->isNotEmpty())
        {
            throw new RuntimeException(sprintf(
                'Cannot rehash doctor_slot_key: %d doctor/date/time group(s) already have active appointments. Resolve them first.',
                $collisions->count()
            ));
        }
    }

    /**
     * @throws Throwable
     */
    private function rehash(callable $keyBuilder): void
    {
        DB::transaction(function () use ($keyBuilder)
        {
            DB::table('appointments')
                ->whereIn('status', ['pending', 'confirmed'])
                ->update(['doctor_slot_key' => null]);

            DB::table('appointments')
                ->whereIn('status', ['pending', 'confirmed'])
                ->chunkById(200, function ($appointments) use ($keyBuilder)
                {
                    foreach ($appointments as $appointment)
                    {
                        DB::table('appointments')
                            ->where('id', $appointment->id)
                            ->update([
                                'doctor_slot_key' => hash('sha256', $keyBuilder($appointment)),
                            ]);
                    }
                });
        });
    }
};
