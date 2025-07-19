<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['jazzcash', 'easypaisa', 'bank_transfer', 'paypal']);
            $table->string('account_name');
            $table->string('account_number', 50);
            $table->string('bank_name')->nullable();
            $table->string('branch_code', 20)->nullable();
            $table->text('user_notes')->nullable();
            $table->decimal('fee_amount', 8, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
