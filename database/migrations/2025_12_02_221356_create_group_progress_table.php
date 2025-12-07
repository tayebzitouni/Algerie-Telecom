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
         Schema::create('group_progress', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('group_id');
        $table->integer('note')->nullable();
        $table->date('date')->nullable();
        $table->timestamps();

        $table->foreign('group_id')->references('id')->on('groups_table')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_progress');
    }
};
