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
        Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->string('user_name')->nullable(); // اسم المستخدم اللي عمل الحركة
        $table->string('action');                 // شو الحركة (مثلاً: إضافة منتج جديد)
        $table->text('details')->nullable();    // تفاصيل إضافية
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
