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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opd_id')->nullable()->constrained('opds')->nullOnDelete();
            $table->foreignId('assigned_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('category');
            $table->string('subdistrict'); // Kecamatan di Kab. Sumbawa
            $table->text('location_detail');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description');
            $table->string('photo_path')->nullable();
            $table->string('urgency')->default('sedang'); // 'rendah', 'sedang', 'mendesak'
            $table->string('status')->default('menunggu_verifikasi'); 
            // 'menunggu_verifikasi', 'didisposisikan', 'diproses', 'selesai', 'ditolak'
            $table->text('admin_notes')->nullable(); // Instruksi disposisi admin ke OPD
            $table->timestamp('dispatched_at')->nullable(); // Waktu admin teruskan ke OPD
            $table->text('opd_notes')->nullable(); // Catatan tindak lanjut dari OPD
            $table->string('resolution_photo_path')->nullable(); // Foto bukti penanganan dari OPD
            $table->text('rejection_reason')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
