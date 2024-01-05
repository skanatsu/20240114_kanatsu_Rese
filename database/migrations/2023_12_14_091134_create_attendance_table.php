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
       Schema::create('attendances', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('user_id');
           $table->string('type');
           $table->timestamp('punch_time');
           $table->timestamp('start_time')->nullable(); // 勤務開始時刻を追加
           $table->timestamp('end_time')->nullable(); // 勤務終了時刻を追加
           $table->timestamp('break_start_time')->nullable(); // 休憩開始時刻を追加
            $table->timestamp('break_end_time')->nullable(); // 休憩終了時刻を追加
           $table->unsignedInteger('break_time')->nullable()->default(0); // 休憩時間を追加
           $table->unsignedInteger('working_time')->nullable()->default(0); // 勤務時間を追加
           $table->timestamps();

           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

       });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('attendances');
   }
};
