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
        Schema::create('info', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['PDF', 'IMAGE', 'TEXT']);
            $table->string('file_path');

            $table->unsignedBigInteger('category_id')
                ->nullable();
            
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete(null);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info');
    }
};