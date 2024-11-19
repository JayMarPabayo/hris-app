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
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('position', 200);
            $table->string('company', 200);
            $table->integer('monthlysalary')->nullable();
            $table->string('paygrade', 10)->nullable();
            $table->string('appointmentstatus', 50);
            // $table->boolean('govtservice');
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->timestamps();

            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
