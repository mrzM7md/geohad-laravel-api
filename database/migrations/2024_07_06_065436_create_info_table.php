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
        Schema::create('infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('title');
            $table->text('content')->nullable()->default(null);
            $table->enum('type', ['PDF', 'IMAGE', 'TEXT']);
            $table->text('file_path')->nullable()->default(null);
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
