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
        Schema::create('patient_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            // Change these fields to boolean with a default value of false
            $table->string('Heart_trouble')->default('0');
            $table->string('pregnancy')->default('0');
            $table->string('Hepatitis')->default('0');
            $table->string('TB')->default('0');
            $table->string('Anemia')->default('0');
            $table->string('Rad_Therapy')->default('0');
            $table->string('Aspirin_intake')->default('0');
            $table->string('Asthma')->default('0');
            $table->string('Hypertension')->default('0');
            $table->string('Diabetes')->default('0');
            $table->string('AIDS')->default('0');
            $table->string('Allergies')->default('0');
            $table->string('Rheu_Arthritis')->default('0');
            $table->string('Hemophilia')->default('0');
            $table->string('Kidney_Trouble')->default('0');
            $table->string('Hey_fever')->default('0');
            // Add other boolean fields with default values if needed
            $table->string('MedicalHistory')->nullable();
            $table->string('ChiefComplain')->nullable();
            $table->string('Diagnosis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_details');
    }
};
