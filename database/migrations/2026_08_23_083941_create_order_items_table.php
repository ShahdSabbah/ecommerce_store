<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // ربط العنصر بالفاتورة
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // ربط العنصر بالمنتج الأصلي
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->integer('quantity'); // الكمية المطلوبة
            $table->decimal('price', 10, 2); // سعر القطعة وقت الشراء
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};