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
       Schema::create('stagiaires', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
         $table->string('city')->nullable();
         
   $table->string('cv_path')->nullable();
        $table->string('student_card_path')->nullable();
        $table->string('cover_letter_path')->nullable();


        $table->enum('status',['pending','approved','refused'])->default('pending');

        $table->unsignedBigInteger('group_id')->nullable();
        $table->timestamps();

    
        $table->foreign('group_id')->references('id')->on('groups_table')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaires');
    }
};
