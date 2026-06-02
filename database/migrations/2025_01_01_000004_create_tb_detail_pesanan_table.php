<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->string('id_pesanan', 50);
            $table->unsignedBigInteger('id_menu');
            $table->integer('kuantitas');
            $table->integer('subtotal');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('tb_pesanan')->cascadeOnDelete();
            $table->foreign('id_menu')->references('id_menu')->on('tb_menu')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_detail_pesanan');
    }
};
