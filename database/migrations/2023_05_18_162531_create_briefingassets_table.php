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
        Schema::create('briefingassets', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('briefing_id')->constrained('briefings')->onUpdate('cascade')->onDelete('cascade'); 
            $table->string('title');
            $table->string('link');
            $table->string('image');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('briefingassets');
    }
};
