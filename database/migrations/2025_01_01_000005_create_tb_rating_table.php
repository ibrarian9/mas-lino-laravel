<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_rating', function (Blueprint $table) {
            $table->id('id_rating');
            $table->string('id_pesanan', 50);
            $table->unsignedBigInteger('id_menu');
            $table->tinyInteger('nilai_bintang');
            $table->text('ulasan')->nullable();
            $table->timestamp('waktu_rating')->useCurrent();
            $table->foreign('id_pesanan')->references('id_pesanan')->on('tb_pesanan')->cascadeOnDelete();
            $table->foreign('id_menu')->references('id_menu')->on('tb_menu')->cascadeOnDelete();
            $table->unique(['id_pesanan', 'id_menu'], 'unique_rating_per_item');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rating');
    }
};
