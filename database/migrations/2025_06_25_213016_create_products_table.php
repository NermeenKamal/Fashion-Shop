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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // اسم المنتج
            $table->string('slug')->unique(); // رابط المنتج
            $table->text('description'); // وصف المنتج
            $table->decimal('price', 10, 2); // السعر
            $table->decimal('sale_price', 10, 2)->nullable(); // سعر التخفيض
            $table->integer('stock')->default(0); // الكمية المتوفرة
            $table->string('image'); // الصورة الرئيسية
            $table->json('images')->nullable(); // صور إضافية
            $table->json('sizes')->nullable(); // المقاسات المتوفرة
            $table->json('colors')->nullable(); // الألوان المتوفرة
            $table->boolean('is_featured')->default(false); // منتج مميز
            $table->boolean('is_active')->default(true); // حالة المنتج
            $table->integer('views')->default(0); // عدد المشاهدات
            $table->string('brand')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
