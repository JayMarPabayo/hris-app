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
        Schema::create('eligibilities', function (Blueprint $table) {
            $table->id();
            $table->string('examination', 150);
            $table->float('rating')->nullable();
            $table->date('examdate')->nullable();
            $table->text('address')->nullable();
            $table->string('license', 40)->unique()->nullable();
            $table->year('validity')->nullable();
            $table->timestamps();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eligibilities');
    }
};
