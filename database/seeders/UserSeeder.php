<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مدير النظام مع صلاحيات كاملة
        User::firstOrCreate(
            ['email' => 'admin@fashionstore.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+966501234567',
                'address' => 'الرياض، المملكة العربية السعودية',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // إنشاء مستخدم عادي للاختبار
        User::firstOrCreate(
            ['email' => 'user@fashionstore.com'],
            [
                'name' => 'مستخدم عادي',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '+966507654321',
                'address' => 'جدة، المملكة العربية السعودية',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('تم إنشاء المستخدمين بنجاح!');
        $this->command->info('بيانات تسجيل الدخول:');
        $this->command->info('مدير النظام: admin@fashionstore.com / admin123');
        $this->command->info('مستخدم عادي: user@fashionstore.com / user123');
        $this->command->info('');
        $this->command->info('ملاحظة: المدير فقط يمكنه إدارة المنتجات والطلبات والمستخدمين');
    }
} 