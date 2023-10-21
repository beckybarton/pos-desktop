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
        Schema::create('cash_on_hand', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id'); 
            $table->float('denomination')->nullable()->default(null);
            $table->float('pcs')->nullable()->default(null);
            $table->float('amount')->nullable()->default(null);
            
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_on_hand');
    }
};
