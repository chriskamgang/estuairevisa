<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_images', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier (e.g., 'about_image', 'banner_bg')
            $table->string('section')->nullable(); // Section name (e.g., 'about', 'banner', 'choose')
            $table->string('label'); // Human-readable label
            $table->string('image'); // Image filename
            $table->text('description')->nullable(); // Optional description
            $table->integer('order')->default(0); // Display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frontend_images');
    }
}
