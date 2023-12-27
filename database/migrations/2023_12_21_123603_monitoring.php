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
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tb_siswa_id')->constrained('tb_siswa')->onDelete('cascade');
            $table->foreignId('tb_guru_id')->constrained('tb_guru')->onDelete('cascade');
            $table->foreignId('tb_kelas_id')->constrained('tb_kelas')->onDelete('cascade');
            $table->foreignId('instances_id')->constrained('instances')->onDelete('cascade');
            $table->date('submission_date');
            $table->date('verification_date');
            $table->boolean('verification_status');
            $table->enum('submission_type', ['submitting', 'switching']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};
