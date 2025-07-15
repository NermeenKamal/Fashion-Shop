<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "المنتجات التي تحتوي على صور:\n";
$products = Product::whereNotNull('image')->get(['id', 'name', 'image']);

foreach ($products as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Image: {$product->image}\n";
}

echo "\nالمنتجات التي تحتوي على صور غير صحيحة (لا تبدأ بـ http):\n";
$invalidProducts = Product::whereNotNull('image')
                         ->where('image', 'not like', 'http%')
                         ->get(['id', 'name', 'image']);

foreach ($invalidProducts as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Image: {$product->image}\n";
} 