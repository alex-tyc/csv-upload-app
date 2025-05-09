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
        Schema::create('csv_files', function (Blueprint $table) {
            $table->id();
            $table->string("product_title");
            $table->string("product_description");
            $table->string("style");
            $table->string("sanmar_mainframe_color");
            $table->string("size");
            $table->string("color_name");
            $table->string("piece_price");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_files');
    }
};
