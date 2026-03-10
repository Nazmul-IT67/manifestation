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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('age')->nullable();
            $table->enum('experience_level', ['beginner', 'intermediate', 'expert'])->nullable();
            $table->string('primary_goal')->nullable();
            $table->string('default_session_duration')->nullable();
            $table->string('preferred_sound_profile')->nullable();
            $table->time('daily_reminder_time')->nullable();
            $table->integer('stat_manifests')->default(0);
            $table->integer('stat_streak')->default(0);
            $table->integer('stat_minutes')->default(0);
            $table->string('location')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
