<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Hapus foreign key yang lama (jika ada)
            $table->dropForeign(['project_id']);  // Pastikan nama kolomnya sesuai

            // Tambahkan constraint foreign key baru dengan onDelete('cascade')
            $table->foreign('project_id')    // Menggunakan foreign untuk kolom yang sudah ada
                ->references('id')           // Mengacu pada kolom 'id' di tabel projects
                ->on('projects')             // Relasi ke tabel 'projects'
                ->onDelete('cascade');       // Menambahkan opsi 'cascade' pada penghapusan
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Hapus foreign key yang baru
            $table->dropForeign(['project_id']);
        });
    }
};
