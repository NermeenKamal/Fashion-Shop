<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            // منتجات نسائية
            [
                'category_id' => $categories->where('slug', 'women-clothing')->first()->id,
                'name' => 'فستان أنيق أسود',
                'slug' => 'elegant-black-dress',
                'description' => 'فستان أنيق أسود مناسب للحفلات والمناسبات الرسمية',
                'price' => 299.99,
                'sale_price' => 249.99,
                'stock' => 50,
                'image' => 'products/women-dress-1.jpg',
                'images' => ['products/women-dress-1-1.jpg', 'products/women-dress-1-2.jpg'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['أسود', 'أزرق داكن'],
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'women-clothing')->first()->id,
                'name' => 'بلوزة أنيقة بيضاء',
                'slug' => 'elegant-white-blouse',
                'description' => 'بلوزة أنيقة بيضاء مناسبة للعمل والمناسبات الرسمية',
                'price' => 149.99,
                'stock' => 75,
                'image' => 'products/women-blouse-1.jpg',
                'sizes' => ['XS', 'S', 'M', 'L'],
                'colors' => ['أبيض', 'أزرق فاتح'],
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'women-clothing')->first()->id,
                'name' => 'جينز كلاسيك',
                'slug' => 'classic-jeans',
                'description' => 'جينز كلاسيك مريح ومناسب للاستخدام اليومي',
                'price' => 199.99,
                'sale_price' => 159.99,
                'stock' => 100,
                'image' => 'products/women-jeans-1.jpg',
                'sizes' => ['26', '28', '30', '32', '34'],
                'colors' => ['أزرق داكن', 'أزرق فاتح'],
                'is_featured' => true,
                'is_active' => true,
            ],

            // منتجات رجالية
            [
                'category_id' => $categories->where('slug', 'men-clothing')->first()->id,
                'name' => 'قميص رسمي أبيض',
                'slug' => 'formal-white-shirt',
                'description' => 'قميص رسمي أبيض مناسب للعمل والمناسبات الرسمية',
                'price' => 179.99,
                'stock' => 60,
                'image' => 'products/men-shirt-1.jpg',
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['أبيض', 'أزرق فاتح'],
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'men-clothing')->first()->id,
                'name' => 'بدلة رسمية رمادية',
                'slug' => 'formal-gray-suit',
                'description' => 'بدلة رسمية رمادية أنيقة ومناسبة للمناسبات المهمة',
                'price' => 899.99,
                'sale_price' => 749.99,
                'stock' => 25,
                'image' => 'products/men-suit-1.jpg',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['رمادي', 'أسود'],
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'men-clothing')->first()->id,
                'name' => 'جينز رجالي كلاسيك',
                'slug' => 'classic-men-jeans',
                'description' => 'جينز رجالي كلاسيك مريح ومناسب للاستخدام اليومي',
                'price' => 249.99,
                'stock' => 80,
                'image' => 'products/men-jeans-1.jpg',
                'sizes' => ['30', '32', '34', '36', '38'],
                'colors' => ['أزرق داكن', 'أزرق فاتح'],
                'is_featured' => false,
                'is_active' => true,
            ],

            // منتجات أطفال
            [
                'category_id' => $categories->where('slug', 'kids-clothing')->first()->id,
                'name' => 'فستان طفلة وردي',
                'slug' => 'pink-girl-dress',
                'description' => 'فستان طفلة وردي جميل ومناسب للحفلات',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock' => 40,
                'image' => 'products/kids-dress-1.jpg',
                'sizes' => ['2Y', '4Y', '6Y', '8Y'],
                'colors' => ['وردي', 'أزرق فاتح'],
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'kids-clothing')->first()->id,
                'name' => 'قميص طفل أزرق',
                'slug' => 'blue-boy-shirt',
                'description' => 'قميص طفل أزرق مريح ومناسب للاستخدام اليومي',
                'price' => 89.99,
                'stock' => 55,
                'image' => 'products/kids-shirt-1.jpg',
                'sizes' => ['2Y', '4Y', '6Y', '8Y', '10Y'],
                'colors' => ['أزرق', 'أحمر'],
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'kids-clothing')->first()->id,
                'name' => 'جينز أطفال مريح',
                'slug' => 'comfortable-kids-jeans',
                'description' => 'جينز أطفال مريح ومناسب للعب والحركة',
                'price' => 119.99,
                'stock' => 70,
                'image' => 'products/kids-jeans-1.jpg',
                'sizes' => ['2Y', '4Y', '6Y', '8Y', '10Y'],
                'colors' => ['أزرق داكن', 'أزرق فاتح'],
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        // ترحيل صور المنتجات القديمة إلى Cloudinary
        $products = Product::where(function($q) {
            $q->whereNull('image')
              ->orWhere('image', 'not like', 'http%');
        })->get();

        foreach ($products as $product) {
            try {
                $localPath = public_path('products/' . $product->image);
                if (file_exists($localPath)) {
                    echo "جاري رفع صورة المنتج: {$product->name} من {$localPath}\n";
                    $uploadedFileUrl = Cloudinary::upload($localPath)->getSecurePath();
                    $product->image = $uploadedFileUrl;
                    $product->save();
                    echo "تم رفع صورة المنتج: {$product->name} إلى {$uploadedFileUrl}\n";
                } else {
                    echo "لم يتم العثور على الصورة للمنتج: {$product->name} في المسار: {$localPath}\n";
                }
            } catch (\Exception $e) {
                echo "خطأ في رفع صورة المنتج {$product->name}: " . $e->getMessage() . "\n";
            }
        }
    }
}
