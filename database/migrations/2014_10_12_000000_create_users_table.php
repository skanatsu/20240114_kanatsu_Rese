<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type')->default('general');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }

    // public function down(): void
    // {
    //     Schema::table('reservations', function (Blueprint $table) {
    //         // 'reservations_user_id_foreign' という名前の外部キー制約を削除
    //         $table->dropForeign('reservations_user_id_foreign');
    //     });

    //     Schema::dropIfExists('users');
    // }

};