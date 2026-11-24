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
        // 1. Kerusakan
        Schema::create('kerusakan', function (Blueprint $table) {
            $table->id('id_kerusakan');
            $table->string('kode_kerusakan', 10)->unique();
            $table->string('nama_kerusakan', 100);
            $table->text('deskripsi')->nullable();
            $table->text('solusi');
            $table->timestamps();
        });

        // 2. Gejala
        Schema::create('gejala', function (Blueprint $table) {
            $table->id('id_gejala');
            $table->string('kode_gejala', 10)->unique();
            $table->string('nama_gejala', 255);
            $table->timestamps();
        });

        // 3. Basis Pengetahuan (Rules CF)
        Schema::create('basis_pengetahuan', function (Blueprint $table) {
            $table->id('id_rule');
            $table->foreignId('id_kerusakan')->constrained('kerusakan', 'id_kerusakan')->cascadeOnDelete();
            $table->foreignId('id_gejala')->constrained('gejala', 'id_gejala')->cascadeOnDelete();
            $table->decimal('mb', 3, 2)->default(0.00);
            $table->decimal('md', 3, 2)->default(0.00);
            $table->decimal('cf_pakar', 4, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['id_kerusakan', 'id_gejala']);
        });

        // 4. Riwayat Diagnosa
        Schema::create('riwayat_diagnosa', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_kerusakan_utama')->nullable()->constrained('kerusakan', 'id_kerusakan')->nullOnDelete();
            $table->decimal('nilai_cf_akhir', 5, 4)->default(0.0000);
            $table->decimal('persentase', 5, 2)->default(0.00);
            $table->text('hasil_lain_json')->nullable();
            $table->timestamp('tanggal_diagnosa')->useCurrent();
            $table->timestamps();
        });

        // 5. Detail Riwayat Diagnosa (Input Gejala & Bobot User)
        Schema::create('detail_riwayat', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_riwayat')->constrained('riwayat_diagnosa', 'id_riwayat')->cascadeOnDelete();
            $table->foreignId('id_gejala')->constrained('gejala', 'id_gejala')->cascadeOnDelete();
            $table->decimal('cf_user', 3, 2);
            $table->string('label_keyakinan', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_riwayat');
        Schema::dropIfExists('riwayat_diagnosa');
        Schema::dropIfExists('basis_pengetahuan');
        Schema::dropIfExists('gejala');
        Schema::dropIfExists('kerusakan');
    }
};

