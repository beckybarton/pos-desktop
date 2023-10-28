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
        Schema::create('excesses', function (Blueprint $table) {
            $table->id();
            $table->float('amount')->nullable()->default(null);
            $table->unsignedBigInteger('payment_id')->default(0); 
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excesses');
    }
};
