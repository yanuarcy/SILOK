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
        Schema::create('whatsapp_api_owners', function (Blueprint $table) {
            $table->id(); // This will be used for the "No" column
            $table->string('name'); // Nama Owner Whatsapp
            $table->string('whatsapp_number')->unique(); // No Whatsapp
            $table->enum('status', ['active', 'inactive'])->default('inactive'); // Status
            $table->string('token', 200); // Token API Whatsapp Fontee
            $table->integer('quota')->default(0); // Quota
            $table->date('subscription_date')->nullable(); // Subscribe
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_api_owners');
    }
};
