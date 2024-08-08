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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('level', 50);
            $table->string('school', 100);
            $table->string('degree', 100);
            $table->year('start');
            $table->year('end')->nullable();
            $table->string('earned')->nullable();
            $table->year('graduated')->nullable();
            $table->text('accolades')->nullable();
            $table->timestamps();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
