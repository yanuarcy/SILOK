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
        // 1. Create layanan table first
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('image');
            $table->text('description');
            $table->string('small');
            $table->boolean('has_sub_layanan')->default(true);
            $table->timestamps();
        });

        // 2. Create sub_layanan table with foreign key to layanan
        Schema::create('sub_layanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layanan_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('image');
            $table->boolean('has_items')->default(false);
            $table->timestamps();

            $table->foreign('layanan_id')
                  ->references('id')
                  ->on('layanan')
                  ->onDelete('cascade');
        });

        // 3. Create layanan_items table with foreign key to sub_layanan
        Schema::create('layanan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_layanan_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('image');
            $table->timestamps();

            $table->foreign('sub_layanan_id')
                  ->references('id')
                  ->on('sub_layanan')
                  ->onDelete('cascade');
        });

        // 4. Create registration_options table
        Schema::create('registration_options', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('image');
            $table->timestamps();
        });

        // 5. Create applicant_types table with foreign key to registration_options
        Schema::create('applicant_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_types');
        Schema::dropIfExists('registration_options');
        Schema::dropIfExists('layanan_items');
        Schema::dropIfExists('sub_layanan');
        Schema::dropIfExists('layanan');
    }
};
