<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booth_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('shift', ['day', 'night']);
            $table->date('date_in');
            $table->date('date_out')->nullable();
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->decimal('amount_collected', 15, 2)->default(0);
            $table->decimal('amount_deposited', 15, 2)->default(0);
            $table->decimal('difference', 15, 2)->default(0);
            $table->string('control_no')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('cashier_name')->nullable();
            $table->enum('control_no_status', ['provided', 'pending'])->default('pending');
            $table->text('comments')->nullable();
            $table->json('flags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_collections');
    }
};
