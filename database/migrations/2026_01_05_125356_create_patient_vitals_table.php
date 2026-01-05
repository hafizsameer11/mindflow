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
        Schema::create('patient_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('bmi')->nullable();
            $table->string('heart_rate')->nullable();
            $table->string('weight')->nullable();
            $table->string('fbc')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('glucose_level')->nullable();
            $table->string('body_temperature')->nullable();
            $table->date('recorded_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_vitals');
    }
};
