<?php

use App\Models\Employee;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 100);
            $table->string('middlename', 100)->nullable();
            $table->string('lastname', 100);
            $table->string('nameextension', 10)->nullable();
            $table->string('designation', 100);
            $table->date('birthdate');
            $table->string('birthplace', 255);
            $table->enum('gender', Employee::$gender);
            $table->string('citizenship', 50);
            $table->enum('civilstatus', Employee::$civilstatus);
            $table->string('residential_houseblock', 50)->nullable();
            $table->string('residential_street', 100)->nullable();
            $table->string('residential_subdivision', 100)->nullable();
            $table->string('residential_barangay', 100)->nullable();
            $table->string('residential_city', 100)->nullable();
            $table->string('residential_province', 100)->nullable();
            $table->string('residential_region', 100)->nullable();
            $table->string('residential_zipcode', 10)->nullable();
            $table->string('permanent_houseblock', 50)->nullable();
            $table->string('permanent_street', 100)->nullable();
            $table->string('permanent_subdivision', 100)->nullable();
            $table->string('permanent_barangay', 100)->nullable();
            $table->string('permanent_city', 100)->nullable();
            $table->string('permanent_province', 100)->nullable();
            $table->string('permanent_region', 100)->nullable();
            $table->string('permanent_zipcode', 10)->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->enum('bloodtype', Employee::$bloodtype);
            $table->string('pagibig', 20)->nullable();
            $table->string('philhealth', 20)->nullable();
            $table->string('sss', 20)->nullable();
            $table->string('tin', 20)->nullable();
            $table->string('agencynumber', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('spouse_firstname', 100)->nullable();
            $table->string('spouse_middlename', 100)->nullable();
            $table->string('spouse_lastname', 100)->nullable();
            $table->string('spouse_nameextension', 10)->nullable();
            $table->string('spouse_occupation', 100)->nullable();
            $table->string('spouse_employerbusiness', 255)->nullable();
            $table->string('spouse_businessaddress', 255)->nullable();
            $table->string('spouse_telephone', 20)->nullable();
            $table->string('father_firstname', 100)->nullable();
            $table->string('father_middlename', 100)->nullable();
            $table->string('father_lastname', 100)->nullable();
            $table->string('father_nameextension', 10)->nullable();
            $table->string('mother_firstname', 100)->nullable();
            $table->string('mother_middlename', 100)->nullable();
            $table->string('mother_lastname', 100)->nullable();
            $table->string('mother_nameextension', 10)->nullable();
            $table->timestamps();

            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
