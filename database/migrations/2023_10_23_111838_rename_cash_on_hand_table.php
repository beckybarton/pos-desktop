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
        Schema::rename('cash_on_hand', 'cash_on_hands');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('cash_on_hand', 'cash_on_hands');
    }
};
