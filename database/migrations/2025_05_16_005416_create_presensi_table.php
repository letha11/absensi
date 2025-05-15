<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->string('karyawan_email');
            $table->foreign('karyawan_email')->references('email')->on('karyawan')->onDelete('cascade');
            $table->date('tgl_presensi');
            $table->time('jam_in');
            $table->time('jam_out')->nullable();
            $table->string('foto_in')->nullable();
            $table->string('foto_out')->nullable();
            $table->text('lokasi_in')->nullable();
            $table->text('lokasi_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
