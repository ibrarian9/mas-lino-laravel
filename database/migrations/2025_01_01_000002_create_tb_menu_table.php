<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu', 150);
            $table->enum('kategori', ['reguler', 'bundling']);
            $table->text('deskripsi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->integer('harga_normal');
            $table->integer('diskon')->default(0);
            $table->integer('harga_c1');
            $table->integer('total_order_c2')->default(0);
            $table->float('rating_rata_rata_c3')->default(3.0);
            $table->integer('jumlah_rating')->default(0);
            $table->boolean('is_bundle')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_menu');
    }
};
