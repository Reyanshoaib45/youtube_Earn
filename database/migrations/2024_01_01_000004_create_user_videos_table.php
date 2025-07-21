<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->decimal('reward_earned', 8, 2);
            $table->integer('watch_duration')->default(0); // Actual watch time in seconds
            $table->string('ip_address')->nullable(); // Track IP for anti-cheat
            $table->string('user_agent')->nullable(); // Track browser for anti-cheat
            $table->timestamps();
            
            // Prevent duplicate watches per day
            $table->unique(['user_id', 'video_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_videos');
    }
};
