<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! app()->environment('local') || ! config('changelog.enabled')) {
            return;
        }

        Schema::create('changelog_entry_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changelog_entry_id')
                ->constrained('changelog_entries')
                ->cascadeOnDelete();
            $table->string('locale', 10);
            $table->text('description');
            $table->timestamps();

            $table->unique(['changelog_entry_id', 'locale']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('changelog_entry_translations');
    }
};
