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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->string('full_name');
            $table->integer('age');
            $table->date('birth_date');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->text('address');
            $table->decimal('weight', 5, 2)->nullable(); // dalam kg
            $table->decimal('height', 5, 2)->nullable(); // dalam cm
            $table->string('phone');
            $table->text('complaint');
            $table->string('complaint_duration')->nullable(); // lama keluhan
            $table->date('therapy_date');
            $table->time('therapy_time');
            $table->string('treatment_type'); // jenis treatment
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('therapist_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['status', 'therapy_date']);
            $table->index(['phone', 'therapy_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
