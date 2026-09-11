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
        if (! app()->environment('local') || ! config('changelog.enabled')) {
            return;
        }

        Schema::create('changelog_entries', function (Blueprint $table) {
            $table->id();
            $table->string('version', 32);
            $table->date('released_at');
            $table->enum('category', [
                'added',
                'changed',
                'fixed',
                'technical',
            ]);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['released_at', 'version']);
            $table->index(['version', 'category', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changelog_entries');
    }
};
