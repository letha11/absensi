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
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
            $table->date('tgl_izin');
            $table->char('status', 1); // 'i' for izin, 's' for sakit
            $table->text('keterangan');
            $table->tinyInteger('status_approved')->default(0); // 0:pending, 1:approved, 2:rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};
