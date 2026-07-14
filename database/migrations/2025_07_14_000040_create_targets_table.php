<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->enum('scope', ['company', 'parking_lot', 'station']);
            $table->foreignId('scope_id')->nullable();
            $table->enum('target_type', ['contractual', 'company_set']);
            $table->enum('metric', ['revenue_tzs', 'vehicle_count']);
            $table->enum('period', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->decimal('target_value', 15, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
