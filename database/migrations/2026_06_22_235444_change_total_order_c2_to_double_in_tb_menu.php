<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_menu', function (Blueprint $table) {
            $table->double('total_order_c2')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tb_menu', function (Blueprint $table) {
            $table->integer('total_order_c2')->default(0)->change();
        });
    }
};
