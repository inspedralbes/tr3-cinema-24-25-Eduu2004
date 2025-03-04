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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session')->constrained('sessions')->onDelete('cascade');
            $table->string('row', 1); 
            $table->integer('number'); 
            $table->enum('type', ['Normal', 'VIP']); 
            $table->enum('status', ['Disponible', 'Ocupada']); 
            $table->timestamps();
            $table->unique(['session_id', 'row', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
