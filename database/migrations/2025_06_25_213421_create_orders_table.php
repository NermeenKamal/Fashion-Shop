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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique(); // رقم الطلب
            $table->decimal('total_amount', 10, 2); // إجمالي المبلغ
            $table->decimal('shipping_cost', 10, 2)->default(0); // تكلفة الشحن
            $table->decimal('tax_amount', 10, 2)->default(0); // الضريبة
            $table->decimal('discount_amount', 10, 2)->default(0); // الخصم
            $table->decimal('final_amount', 10, 2); // المبلغ النهائي
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('shipping_address'); // عنوان الشحن
            $table->string('billing_address'); // عنوان الفواتير
            $table->string('phone'); // رقم الهاتف
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamp('shipped_at')->nullable(); // تاريخ الشحن
            $table->timestamp('delivered_at')->nullable(); // تاريخ التسليم
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
