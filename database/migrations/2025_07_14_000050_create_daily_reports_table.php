<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->integer('vehicle_count')->default(0);
            $table->decimal('expenses', 15, 2)->default(0);
            $table->decimal('net_revenue', 15, 2)->default(0);
            $table->text('comments')->nullable();
            $table->enum('source', ['manual', 'excel'])->default('manual');
            $table->string('file_path')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();

            $table->unique(['station_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
