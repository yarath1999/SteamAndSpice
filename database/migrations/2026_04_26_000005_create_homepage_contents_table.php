<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Steam & Spice');
            $table->text('hero_subtitle')->nullable();
            $table->string('intro_title')->default('A London Dining Story');
            $table->text('intro_text')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('intro_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};
