<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // référence à la table users
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // référence à la table products
            $table->timestamps();

            $table->unique(['user_id', 'product_id']); // évite les doublons
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
