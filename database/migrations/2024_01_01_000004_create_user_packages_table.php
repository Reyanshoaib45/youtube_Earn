<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_screenshot')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('payment_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('videos_watched')->default(0);
            $table->decimal('total_earned', 10, 2)->default(0);
             $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('referral_earnings', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_packages');
    }
};