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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->text('profile_description_en')->nullable();
            $table->text('profile_description_ar')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('location');
            $table->string('city', 100);
            $table->text('address')->nullable();
            $table->enum('subscription_status', ['free', 'premium', 'suspended'])->default('free');
            $table->date('subscription_expiry')->nullable();
            $table->integer('free_quotes_used')->default(0);
            $table->integer('free_quotes_limit')->default(10);
            $table->boolean('is_featured')->default(false);
            $table->decimal('rating', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
