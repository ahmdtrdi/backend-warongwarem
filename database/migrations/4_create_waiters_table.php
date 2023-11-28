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
        if (Schema::hasTable('waiters')) return;

        Schema::create('waiters', function (Blueprint $table) {
            $table->id('waiters_id');
            $table->unsignedBigInteger('user_id');
            $table->string('username');
            $table->foreign('username')->references('username')->on('users');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiters');
    }
};
