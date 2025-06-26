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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // FK ke tabel users
            $table->string('title');
            $table->date('meeting_date');
            $table->time('meeting_time');
            $table->string('meet_link');
            $table->json('participants'); // Store as JSON array
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'active', 'completed'])->default('scheduled');
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['meeting_date', 'meeting_time']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
