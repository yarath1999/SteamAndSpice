<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Steam & Spice');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_tagline')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('intro_title')->default('A Warm Welcome');
            $table->text('intro_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
