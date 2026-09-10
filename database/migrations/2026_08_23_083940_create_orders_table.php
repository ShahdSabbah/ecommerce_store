<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');  // اسم الزبون
        $table->string('customer_email'); // إيميل الزبون للتواصل
        $table->string('customer_phone'); // رقم الهاتف للشحن
        $table->text('address');          // عنوان التوصيل التفصيلي
        $table->decimal('total_price', 10, 2); // السعر الإجمالي الكلي للفاتورة
        
        // حالة الطلب (نستخدم enum لتحديد خيارات محددة فقط لا يمكن الخروج عنها)
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        
        $table->timestamps(); // تاريخ ووقت إنشاء الطلب
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
