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
    Schema::create('menu_items', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->enum('type', ['breakfast', 'snack1', 'snack2', 'lunch']);
        $table->integer('day'); // 1 = понедельник, ..., 7 = воскресенье
        $table->integer('weight'); // в граммах
        $table->integer('price'); // в тенге
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
