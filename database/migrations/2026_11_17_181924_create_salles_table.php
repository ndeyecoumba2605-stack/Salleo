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
    Schema::create('salles', function (Blueprint $table) {
        $table->id();
        $table->string('numero');
        $table->string('batiment');
        $table->unsignedSmallInteger('capacite');
        $table->json('equipements')->nullable();
        $table->boolean('active')->default(true);
        $table->boolean('validation_requise')->default(false);
        $table->timestamps();
        $table->unique(['batiment', 'numero']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
