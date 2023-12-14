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
        if (Schema::hasTable('reservation')) return;

        Schema::create('reservation', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->string('name');
            $table->text('notes')->nullable();
            $table->string('table_type');
            $table->integer('people');
            $table->time('time');
            $table->date('date');
            $table->string('phone_number');
            $table->string('status')->default('unpaid');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('table_id')->references('table_id')->on('table_list');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
