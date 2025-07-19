<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watched_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->integer('watched_seconds')->default(0);
            $table->decimal('watch_percentage', 5, 2)->default(0);
            $table->boolean('is_completed')->default(false);
            $table->boolean('reward_granted')->default(false);
            $table->decimal('reward_amount', 8, 2)->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'video_id']);
            $table->index(['user_id', 'is_completed']);
            $table->index(['video_id', 'is_completed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watched_videos');
    }
};
