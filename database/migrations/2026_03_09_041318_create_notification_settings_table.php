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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('general_notif')->default(true);
            $table->boolean('weekly_summary')->default(true);
            $table->boolean('goal_achievement')->default(true);
            $table->boolean('milestone_celebration')->default(true);
            $table->boolean('subscription_alert')->default(true);
            $table->boolean('social_community')->default(true);
            $table->boolean('feedback_request')->default(true);
            $table->boolean('important_announce')->default(true);
            $table->boolean('tips_tutorials')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
