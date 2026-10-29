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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('warga')->after('email'); // 'admin', 'warga', 'opd'
            $table->foreignId('opd_id')->nullable()->after('role')->constrained('opds')->nullOnDelete();
            $table->string('nik', 20)->nullable()->after('opd_id');
            $table->string('phone', 25)->nullable()->after('nik');
            $table->string('subdistrict')->nullable()->after('phone'); // Kecamatan
            $table->text('address')->nullable()->after('subdistrict');
            $table->string('nip', 30)->nullable()->after('address'); // Untuk Perangkat Daerah
            $table->string('position')->nullable()->after('nip'); // Jabatan Perangkat Daerah
            $table->string('status')->default('active')->after('position'); // 'active', 'pending_approval'
            $table->string('avatar')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn([
                'role',
                'opd_id',
                'nik',
                'phone',
                'subdistrict',
                'address',
                'nip',
                'position',
                'status',
                'avatar',
            ]);
        });
    }
};
