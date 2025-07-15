<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "مسح الصورة المتبقية...\n";

$product = Product::find(18);
if ($product) {
    echo "تم العثور على المنتج: {$product->name}\n";
    echo "الصورة الحالية: {$product->image}\n";
    
    $product->image = null;
    $product->save();
    
    echo "تم مسح الصورة بنجاح!\n";
} else {
    echo "لم يتم العثور على المنتج\n";
} 