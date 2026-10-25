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
        Schema::create('lab_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('laboratory_id')->constrained('laboratories')->onDelete('cascade');
            $table->string('borrower_name');
            $table->string('borrower_type')->default('mahasiswa'); // mahasiswa, dosen, tendik, unit_fakultas, umum
            $table->string('institution_name'); // Nama Fakultas / Prodi / Instansi
            $table->string('borrower_id_number')->nullable(); // NIM / NIP
            $table->string('email');
            $table->string('phone');
            $table->string('event_name');
            $table->text('event_description')->nullable();
            $table->date('booking_date');
            $table->date('end_date')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('participant_count')->default(1);
            $table->string('document_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes for faster lookups
            $table->index(['booking_date', 'status']);
            $table->index(['laboratory_id', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_bookings');
    }
};
