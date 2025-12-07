<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('groups_table', function (Blueprint $table) {
        $table->id();
        $table->string('name');
                           $table->string('program')->nullable();
$table->unsignedBigInteger('theme_id')->nullable();
      $table->unsignedBigInteger('emploi_id')->nullable();
        $table->unsignedBigInteger('ecole_id');
        $table->timestamps();
 $table->foreign('emploi_id')->references('id')->on('emplois')->onDelete('cascade');
        $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
        $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('groups_table'); // ✅

    }
};
