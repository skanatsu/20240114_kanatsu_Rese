<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shopname');
            $table->string('area');
            $table->string('genre');
            $table->string('description');
            $table->string('image_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }

    // public function down(): void
    // {
    //     Schema::table('reservations', function (Blueprint $table) {
    //         // 'reservations_shop_id_foreign' という名前の外部キー制約を削除
    //         $table->dropForeign('reservations_shop_id_foreign');
    //     });

    //     Schema::dropIfExists('shops');
    // }
};