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
        Schema::dropIfExists('media');
        
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->integer('model_id');
            $table->string('media_link')->nullable();
            $table->string('type', 20)->default('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
