<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pesanan', function (Blueprint $table) {
            $table->string('id_pesanan', 50)->primary();
            $table->string('no_meja', 10);
            $table->integer('total_bayar');
            $table->enum('metode_bayar', ['tunai', 'non_tunai']);
            $table->enum('status_pembayaran', ['menunggu', 'lunas', 'gagal', 'kedaluwarsa'])->default('menunggu');
            $table->enum('status_pesanan', ['baru', 'diproses', 'selesai', 'dibatalkan'])->default('baru');
            $table->string('midtrans_order_id', 100)->nullable();
            $table->timestamp('waktu_pesan')->useCurrent();
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('tb_admin')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pesanan');
    }
};
