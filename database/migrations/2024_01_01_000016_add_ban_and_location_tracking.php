<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add ban fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false);
            $table->text('ban_reason')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->unsignedBigInteger('banned_by')->nullable();
            $table->string('registration_ip')->nullable();
            $table->string('current_ip')->nullable();
            $table->string('current_location')->nullable();
            $table->json('location_data')->nullable();
            
            $table->foreign('banned_by')->references('id')->on('users')->onDelete('set null');
        });

        // Create IP tracking table
        Schema::create('ip_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->integer('account_count')->default(1);
            $table->boolean('is_flagged')->default(false);
            $table->text('flagged_reason')->nullable();
            $table->timestamp('first_seen_at')->nullable();
$table->timestamp('last_seen_at')->nullable();

            $table->json('user_ids')->nullable();
            $table->timestamps();
            
            $table->unique('ip_address');
        });

        // Create location logs table
        Schema::create('location_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address');
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone')->nullable();
            $table->string('isp')->nullable();
            $table->string('action_type'); // login, logout, activity
            $table->string('user_agent')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'created_at']);
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['banned_by']);
            $table->dropColumn([
                'is_banned', 'ban_reason', 'banned_at', 'banned_by',
                'registration_ip', 'current_ip', 'current_location', 'location_data'
            ]);
        });
        
        Schema::dropIfExists('location_logs');
        Schema::dropIfExists('ip_tracking');
    }
};