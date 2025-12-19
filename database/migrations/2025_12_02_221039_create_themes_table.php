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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();

            // Basic information
            $table->string('name'); // Subject Title
            $table->string('category');
            $table->integer('duration'); // weeks

            // Description
            $table->text('description');

            // Requirements & skills
            $table->text('requirements');

            // Learning objectives
            $table->text('learning_objectives');

            // Additional details
            $table->integer('max_capacity')->default(1);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);

            // Optional documentation
            $table->string('documentation_path')->nullable();

            // Foreign key to employee (emploi)
            $table->foreignId('employee_id')->constrained('emplois')->onDelete('cascade');

            $table->timestamps(); 
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
