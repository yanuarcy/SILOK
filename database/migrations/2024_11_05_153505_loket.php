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
        Schema::create('lokets', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->nullable();
            $table->integer('loket_number');
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->enum('call_status', ['standby', 'calling'])->default('standby');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active')->nullable();
            $table->timestamps();

            // Memastikan nomor loket tidak duplikat
            $table->unique('loket_number');

            // Definisi foreign key untuk string
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokets');
    }
};
