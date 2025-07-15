<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ملابس نسائية',
                'slug' => 'women-clothing',
                'description' => 'تشكيلة واسعة من الملابس النسائية العصرية والأنيقة',
                'image' => 'categories/women.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'ملابس رجالية',
                'slug' => 'men-clothing',
                'description' => 'ملابس رجالية عصرية وأنيقة تناسب جميع المناسبات',
                'image' => 'categories/men.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'ملابس أطفال',
                'slug' => 'kids-clothing',
                'description' => 'ملابس أطفال مريحة وجميلة بألوان وأشكال مميزة',
                'image' => 'categories/kids.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
