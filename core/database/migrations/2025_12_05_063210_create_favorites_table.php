<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('favorable_type'); // Type de l'entité (Plan, Country, etc.)
            $table->unsignedBigInteger('favorable_id'); // ID de l'entité
            $table->string('collection')->nullable(); // Pour organiser par collections
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'favorable_type', 'favorable_id']);
            $table->unique(['user_id', 'favorable_type', 'favorable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
